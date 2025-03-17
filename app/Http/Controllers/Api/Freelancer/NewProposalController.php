<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewProposalRequest;
use App\Models\NewProposal;
use App\Models\User;
use App\Notifications\NewProposalReceived;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class NewProposalController extends Controller
{
    use ApiResponseTrait;

    /**
     * Store a new proposal dynamically for different equipment types.
     */
    public function store(StoreNewProposalRequest $request, $jobType, $jobId)
    {
        $validatedData = $request->validated();

        // Dynamically determine the requestable type based on input
        $modelClass = $this->getModelClassFromType($jobType);

        if (!$modelClass) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid job type provided.',
            ], 400);
        }

        // Retrieve the associated job request dynamically
        $requestEntry = \App\Models\Request::where('requestable_type', $modelClass)
            ->where('requestable_id', $jobId)
            ->first();
        if (!$requestEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No matching request found for the provided job type.',
            ], 400);
        }

        // Set the necessary fields dynamically
        $validatedData['request_id'] = $requestEntry->id;
        $validatedData['user_id'] = Auth::id();

        // Create the proposal dynamically
        $proposal = NewProposal::create($validatedData);

        // Get the recipient user (the one who posted the request)
        $recipientUser = User::find($requestEntry->user_id);

        // Send the notification
        if ($recipientUser) {
            $recipientUser->notify(new NewProposalReceived($proposal));
        }

        return response()->json([
            'message' => 'Proposal created successfully.',
            'category_slug' => $jobType,
            'offer' => $proposal
        ]);
    }

    /**
     * Dynamically map job type to its corresponding model class.
     */
    private function getModelClassFromType($type)
    {
        $types = [
            MachineType::heavyEquipment->value => \App\Models\HeavyEquipmentJob::class,
            MachineType::vehicleRental->value => \App\Models\VehicleRentalJob::class,
            MachineType::craneRental->value => \App\Models\CraneRentalJob::class,
            // Add other sub-category models here
        ];

        return $types[$type] ?? null;
    }
}
