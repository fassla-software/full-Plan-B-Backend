<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\Firebase\FcmClient;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;


class FcmNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $notificationData;

    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        // Get the FCM token for the notifiable entity (user or device)
        $token = $notifiable->routeNotificationFor('fcm');

        // Create the FirebaseNotification instance
        $notification = FirebaseNotification::create(
            $this->notificationData['title'],
            $this->notificationData['body']
        );

        // Create the CloudMessage instance with target token and data
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($this->notificationData['data'] ?? []);

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->notificationData['title'],
            'body' => $this->notificationData['body'],
            'data' => $this->notificationData['data'] ?? [],
        ];
    }
}
