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
}
