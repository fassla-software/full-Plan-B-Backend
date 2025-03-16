<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewProposalReceived extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $proposal;

    public function __construct($proposal)
    {
        $this->proposal = $proposal;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Proposal Received',
            'message' => 'You have received a new proposal for your request.',
            'proposal_id' => $this->proposal->id,
            'request_id' => $this->proposal->request_id,
            'sender_id' => $this->proposal->user_id
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->proposal->request->user_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => 'You have a new proposal from user ID ' . $this->proposal->user_id
        ];
    }
}
