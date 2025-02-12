<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewProposalRequest;
use App\Models\NewProposal;
use Illuminate\Http\Request;

class NewProposalController extends Controller
{
    public function store(StoreNewProposalRequest $request, $request_id)
    {
        $validatedData = $request->validated();

        $validatedData['request_id'] = $request_id;
        $validatedData['user_id'] = auth('sanctum')->id();

        $proposal = NewProposal::create($validatedData);

        return response()->json([
            'message' => 'Proposal created successfully',
            'proposal' => $proposal
        ], 201);
    }
}
