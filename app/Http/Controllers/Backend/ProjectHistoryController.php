<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectHistory;

class ProjectHistoryController extends Controller
{
    // all history
    public function all_history()
    {
        $all_histories = ProjectHistory::with('project')
            ->whereHas('project.project_creator')
            ->latest()
            ->paginate(10);
        return view('backend.pages.project-history.all-history',compact('all_histories'));
    }

    // search history
    public function search_history(Request $request)
    {
        $all_histories = ProjectHistory::whereHas('project.project_creator')->whereHas('project', function ($query) use ($request){
            $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })
            ->with(['project' => function($query) use ($request){
                $query->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%");
            }])->paginate(10);

        return $all_histories->total() >= 1 ? view('backend.pages.project-history.search-result', compact('all_histories'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_histories = ProjectHistory::whereHas('project.project_creator')->with('project')->latest()->paginate(10);
            return view('backend.pages.project-history.search-result', compact('all_histories'))->render();
        }
    }
}
