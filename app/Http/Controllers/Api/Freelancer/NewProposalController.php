<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewProposalRequest;
use App\Models\NewProposal;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\HeavyEquipmentJob;
class NewProposalController extends Controller
{
    use ApiResponseTrait;
  
public function store(StoreNewProposalRequest $request, $heavyEquipmentJobId)
{
    $validatedData = $request->validated();
  
    // Get the corresponding `requests.id` from `HeavyEquipmentJob.id`
    $requestEntry = \App\Models\Request::where('requestable_type', HeavyEquipmentJob::class)
                           ->where('requestable_id', $heavyEquipmentJobId)
                           ->first();

    if (!$requestEntry) {
        return response()->json([
            'success' => false,
            'message' => 'No matching request found for this HeavyEquipmentJob.',
        ], 400);
    }

    // Set the correct `request_id`
    $validatedData['request_id'] = $requestEntry->id;
    $validatedData['user_id'] = auth('sanctum')->id();

    // Create proposal
    $proposal = NewProposal::create($validatedData);

    return $this->successResponse($proposal, 'Proposal created successfully', 201);
}
  
  /*public function store(StoreNewProposalRequest $request, $requestableId)
{
    $validatedData = $request->validated();

    // Find the matching request based on requestable_id
    $requestEntry = Request::where('requestable_id', $requestableId)->first();

    if (!$requestEntry) {
        return response()->json([
            'success' => false,
            'message' => 'No matching request found for this requestable_id.',
        ], 400);
    }

    // Set the correct `request_id`
    $validatedData['request_id'] = $requestEntry->id;
    $validatedData['user_id'] = auth('sanctum')->id();

    // Create the proposal
    $proposal = NewProposal::create($validatedData);

    return $this->successResponse($proposal, 'Proposal created successfully', 201);
}*/
}
