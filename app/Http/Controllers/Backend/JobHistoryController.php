<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JobHistory;
use Illuminate\Http\Request;

class JobHistoryController extends Controller
{
    public function all_history()
    {
        $all_histories = JobHistory::with('job')->whereHas('job.job_creator')->latest()->paginate(10);
        return view('backend.pages.job-history.all-history',compact('all_histories'));
    }

    // search history
    public function search_history(Request $request)
    {
        $all_histories = JobHistory::whereHas('job.job_creator')->whereHas('job', function ($query) use ($request){
            $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })
            ->with(['job' => function($query) use ($request){
                $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
            }])->paginate(10);

        return $all_histories->total() >= 1 ? view('backend.pages.job-history.search-result', compact('all_histories'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search)){
                $all_histories = JobHistory::with('job')->whereHas('job.job_creator')->latest()->paginate(10);
                return view('backend.pages.job-history.search-result', compact('all_histories'))->render();
            }else{
                $all_histories = JobHistory::whereHas('job.job_creator')->whereHas('job', function ($query) use ($request){
                    $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
                })
                    ->with(['job' => function($query) use ($request){
                        $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
                    }])->paginate(10);
                return $all_histories->total() >= 1 ? view('backend.pages.job-history.search-result', compact('all_histories'))->render() : response()->json(['status'=>__('nothing')]);
            }
        }
    }
}
