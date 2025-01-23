<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Http;
use Modules\User\Entities\User;
use Modules\Vendor\Entities\Vendor;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class LiveChatMessage extends Model
{
    protected $fillable = [
        "live_chat_id",
        "from_user",
        "message",
        "file",
        'load_from',
        'is_synced'
    ];

    protected $casts = [
        "message" => "json",
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "is_seen" => "integer"
    ];

    public function liveChat(): BelongsTo
    {
        return $this->belongsTo(LiveChat::class,"live_chat_id","id");
    }

    public function client(): HasManyThrough
    {
        return $this->hasManyThrough(User::class,LiveChat::class,'live_chat_id','id','id','client_id');
    }

    public function freelancer(): HasManyThrough
    {
        return $this->hasManyThrough(User::class,LiveChat::class,'live_chat_id','id','id','freelancer_id');
    }

    //: this method will be return file path
    public function getFilePathAttribute(){
        return $this->file;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($modal){
            // first check who is the sender of this message if this is a client, then send notification to the freelancer
            // get user from the message

            $freelancer = $modal->liveChat->freelancer;
            $user = $modal->liveChat->client;

            $notificationBody = [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $modal->from_user == 1 ? $user->first_name : $freelancer->first_name,
                'id' => (string) $modal->id,  // Convert to string
                'body' => is_array($modal->message) ? json_encode($modal->message) : (string) $modal->message, // Convert message to string
                'file' => $modal->file ? (string) $modal->file : '',  // Convert file to string if exists
                'description' => '',
                'type' => 'message',
                'sound' => 'default',
                'fcm_device' => '',
                'livechat' => json_encode($modal->liveChat),  // Convert livechat object/array to string
            ];

            try {
                if($modal->from_user){
                    $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

                    // Load the credentials from the JSON file
                    $jsonCredentials = file_get_contents($credentialsPath);
                    $credentials = json_decode($jsonCredentials, true);

                    // Convert to JSON
                    $jsonCredentials = json_encode($credentials);

                    // Initialize Firebase Admin SDK
                    $factory = (new Factory)->withServiceAccount($jsonCredentials);
                    $messaging = $factory->createMessaging();

                    // Construct the message
                    $message = CloudMessage::new()
//                        ->withNotification(Notification::create('message', $modal->message['message'] ?? ''))
                        ->withData($notificationBody);
                    $response = $messaging->sendMulticast($message,$modal->from_user == 1 ? $freelancer->firebase_device_token : $user->firebase_device_token);
                }
            }catch (\Exception $e){}
        });
    }
}
