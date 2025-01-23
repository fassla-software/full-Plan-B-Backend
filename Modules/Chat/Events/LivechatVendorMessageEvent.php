<?php

namespace Modules\Chat\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LivechatVendorMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $client_id, $freelancer_id;
    public $message, $livechat, $messageBlade;

    public function __construct(string $messageBlade,$message, $livechat,$client_id,$freelancer_id)
    {
        $this->messageBlade = $messageBlade;
        $this->message = $message;
        $this->livechat = $livechat;
        $this->freelancer_id = $freelancer_id;
        $this->client_id = $client_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('livechat-freelancer-channel.' . $this->client_id . '.' . $this->freelancer_id),
        ];
    }

    function broadcastAs(): string
    {
        return 'livechat-freelancer-'.$this->freelancer_id;
    }
}
