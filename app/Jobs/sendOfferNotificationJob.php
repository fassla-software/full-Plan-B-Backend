<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\NewProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Services\OfferManagementService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class sendOfferNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $recipientUser;
    protected $proposal;

    public function __construct(User $recipientUser, NewProposal $proposal)
    {
        $this->recipientUser = $recipientUser;
        $this->proposal = $proposal;
    }

    /**
     * Execute the job.
     */
    public function handle(OfferManagementService $offerService)
    {
        $offerService->pushNotification($this->recipientUser, $this->proposal);
    }
}
