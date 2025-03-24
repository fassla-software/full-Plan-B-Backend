<?php

namespace App\Services\Firebase;

use Google\Client as GoogleClient;
use GuzzleHttp\Client as HttpClient;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmClient
{
    private $googleClient;
    private $httpClient;

    public function __construct()
    {
        $this->googleClient = new GoogleClient();
        $this->googleClient->setAuthConfig(storage_path('app/firebase/firebase_credentials.json'));
        $this->googleClient->addScope('https://fcm.googleapis.com/auth/firebase.messaging');
        $this->httpClient = new HttpClient();
    }

    public function sendMessage($token, $notification)
    {
        try {
            $tokenResponse = $this->googleClient->fetchAccessTokenWithAssertion();
            \Log::info('Access Token Response:', $tokenResponse);
            $accessToken = $tokenResponse['access_token'] ?? null;
            if (!$accessToken) {
                \Log::error('Failed to retrieve access token', $tokenResponse);
                throw new \Exception('Failed to retrieve access token');
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching access token: ' . $e->getMessage());
            return ['error' => 'Could not fetch access token'];
        }

        $fcmUrl = 'https://fcm.googleapis.com/v1/projects/plan-b-4ae7c/messages:send';

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $notification['title'],
                    'body'  => $notification['body'],
                ],
                'data' => $notification['data'] ?? [],
            ],
        ];

        try {
            // Make the HTTP request to FCM API
            $response = $this->httpClient->post($fcmUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);
            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Error sending FCM message: ' . $e->getMessage());
            return ['error' => 'Failed to send message'];
        }
    }
}
