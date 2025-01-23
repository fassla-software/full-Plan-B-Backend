<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\Entities\UserSubscription;

class ProposalController extends Controller
{
    public function all_proposal()
    {
        $all_proposals = JobProposal::with('job')
            ->where('freelancer_id',auth()->user()->id)
            ->latest()
            ->whereHas('job.job_creator')
            ->paginate(10);

        $jobs = JobPost::with('job_creator','job_skills')
            ->whereHas('job_creator')
            ->where('on_off','1')
            ->where('status','1')
            ->where('job_approve_request','1')
            ->latest()
            ->take(5)->get();

        return view('frontend.user.freelancer.proposal.proposals',compact(['all_proposals','jobs']));
    }

    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_proposals = JobProposal::with('job')
                ->where('freelancer_id',auth()->user()->id)
                ->latest()
                ->paginate(10);
            return view('frontend.user.freelancer.proposal.search-result', compact(['all_proposals']))->render();
        }
    }
}
