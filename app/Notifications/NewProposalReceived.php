<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => "You've Got a New Offer!",
            'message' => "Your request just got a response! Open now to review the offer and move forward.",
            'proposal_id' => $this->proposal->id,
            'request_id' => $this->proposal->request_id,
            'sender_id' => $this->proposal->user_id
        ];
    }
}
