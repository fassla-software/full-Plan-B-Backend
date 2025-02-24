<?php

namespace App\Services;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Arr;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseBroadcaster extends Broadcaster
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function auth($request)
    {
        return true;
    }

    public function validAuthenticationResponse($request, $result)
    {
        return response()->json(['status' => 'success']);
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach ($channels as $channel) {
            $message = CloudMessage::new()
                ->withTarget('topic', $channel)
                ->withNotification([
                    'title' => $event,
                    'body' => Arr::get($payload, 'message', 'New Notification'),
                ])
                ->withData($payload);

            $this->messaging->send($message);
        }
    }
}
