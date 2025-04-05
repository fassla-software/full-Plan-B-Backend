<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\RequestManagementService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRequestNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $result;
    protected $subCategory;

    public function __construct($result, $subCategory)
    {
        $this->result = $result;
        $this->subCategory = $subCategory;
    }

    /**
     * Execute the job.
     */
    public function handle(RequestManagementService $requestService): void
    {
        $requestService->pushNotification($this->result, $this->subCategory);
    }
}
