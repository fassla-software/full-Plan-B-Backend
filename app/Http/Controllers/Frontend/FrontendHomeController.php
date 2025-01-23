<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;

class FrontendHomeController extends Controller
{
    public function project_or_job_search(Request $request)
    {
        $search_type = $request->search_type ?? '';
        if($search_type == 'project')
        {
            $projects_or_jobs = Project::with('project_creator')
                ->select(['id','title','slug','user_id','basic_regular_charge','image'])
                ->where('project_on_off','1')
                ->where('status','1')
                ->latest()
                ->where('title','LIKE','%'.strip_tags($request->job_search_string).'%')->get();
        }else if($search_type == 'job'){
            $projects_or_jobs = JobPost::with('job_creator','job_skills')
                ->select('id','title','slug','user_id','budget')
                ->where('on_off','1')
                ->where('status','1')
                ->where('job_approve_request','1')
                ->latest()
                ->where('title','LIKE','%'.strip_tags($request->job_search_string).'%')->get();
        }else{
            $projects_or_jobs =  User::with('user_introduction')
                ->select('id', 'username','first_name','last_name','image','country_id','state_id')
                ->where('user_type','2')
                ->where('is_email_verified',1)
                ->where('is_suspend',0)
                ->whereHas('user_introduction', function($q) use ($request){
                $q->where('title', 'LIKE', '%'.strip_tags($request->job_search_string).'%');
            })->get();
        }
        return view('frontend.pages.frontend-home-job-search-result',compact(['projects_or_jobs','search_type']))->render();
    }
}
