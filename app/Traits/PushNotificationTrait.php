<?php

namespace App\Traits;

// use Illuminate\Support\Facades\Http;
// use Google\Auth\ApplicationDefaultCredentials;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

trait PushNotificationTrait
{
    // public function sendNotification($token, $title, $body, $data = [])
    // {
    //     $fcmurl = "https://fcm.googleapis.com/v1/projects/plan-b-d595e/messages:send";

    //     $notification = [
    //         'notification' => [
    //             'title' => $title,
    //             'body' => $body,
    //         ],
    //         'data' => $data,
    //         'token' => $token,
    //     ];

    //     try {
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . $this->getAccessToken(),
    //             'content-Type' => 'application/json',
    //         ])->post($fcmurl, ['message' => $notification]);
    //         dd($response);
    //         return $response->json();
    //     } catch (\Exception $e) {
    //         Log::error("Error sending notification to $token");
    //         return false;
    //     }
    // }

    protected function sendNotification($token, $title, $body, array $data = []): bool
    {
        $messaging = app(Messaging::class);

        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($data);

        try {
            $messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error("Error sending notification: " . $e->getMessage());
            return false;
        }
    }

    protected function sendMulticastNotification(array $tokens, string $title, string $body, array $data = []): bool
    {
        $messaging = app(Messaging::class);

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData($data);

        try {
            $messaging->sendMulticast($message, $tokens);
            return true;
        } catch (\Exception $e) {
            Log::error("Error sending multicast notification: " . $e->getMessage());
            return false;
        }
    }

    // public function getAccessToken()
    // {
    //     $keyPath = config('services.firebase.key_path');
    //     putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyPath);
    //     $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
    //     $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
    //     $token = $credentials->fetchAuthToken();
    //     return $token['access_token'] ?? null;
    // }
}
