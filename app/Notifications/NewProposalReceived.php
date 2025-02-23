<?php

namespace App\Notifications;

use App\Models\NewProposal;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProposalReceived extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(NewProposal $proposal)
    {
        $this->proposal = $proposal;
    }

    /**
     * Define which channels the notification should be sent through.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', 'firebase']; // Added broadcast for Firebase
    }

    /**
     * Store the notification data in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Proposal Received',
            'message' => 'You have received a new proposal for your request.',
            'proposal_id' => $this->proposal->id,
            'request_id' => $this->proposal->request_id,
            'sender_id' => $this->proposal->user_id
        ];
    }

    /**
     * Broadcast the notification for real-time updates.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'New Proposal Received',
            'message' => 'You have received a new proposal for your request.',
            'proposal_id' => $this->proposal->id,
            'request_id' => $this->proposal->request_id,
            'sender_id' => $this->proposal->user_id
        ]);
    }

    /**
     * Define the broadcast channel.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->proposal->request->user_id);
    }
}
