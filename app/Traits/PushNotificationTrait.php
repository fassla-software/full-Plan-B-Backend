<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Google\Auth\ApplicationDefaultCredentials;

trait PushNotificationTrait
{
    public function sendNotification($token, $title, $body, $data = [])
    {
        $fcmurl = "https://fcm.googleapis.com/v1/projects/plan-b-4ae7c/messages:send";

        $notification = [
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
            'token' => $token,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'content-Type' => 'application/json',
            ])->post($fcmurl, ['message' => $notification]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Error sending notification to $token");
            return false;
        }
    }

    public function getAccessToken()
    {
        $keyPath = config('services.firebase.key_path');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyPath);
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
        $token = $credentials->fetchAuthToken();
        return $token['access_token'] ?? null;
    }
}
