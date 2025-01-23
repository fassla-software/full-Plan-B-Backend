<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Modules\Service\Entities\Category;

class CategoryJobController extends Controller
{
    public function category_jobs($slug)
    {
        $category = Category::select('id','category','meta_title','meta_description')->where('slug',$slug)->first();
        if(!empty($category)){
            $query = JobPost::with('job_creator','job_skills')
                ->whereHas('job_creator')
                ->where('on_off','1')
                ->where('status','1')
                ->withCount('job_proposals')
                ->where('job_approve_request','1')
                ->where('category',$category->id)
                ->latest();

            if(moduleExists('HourlyJob')){
                $jobs = $query->paginate(10);
            }else{
                $jobs = $query->where('type','fixed')->paginate(10);
            }
            return view('frontend.pages.category-jobs.jobs',compact('category','jobs'));
        }
        return back();
    }

    public function category_jobs_filter(Request $request)
    {
        if($request->ajax()){
            $query = JobPost::with('job_creator','job_skills')
                ->whereHas('job_creator')
                ->where('on_off','1')
                ->where('status','1')
                ->withCount('job_proposals')
                ->where('job_approve_request','1')
                ->where('category',$request->category)
                ->latest();

            if(moduleExists('HourlyJob')){
                $jobs = $query;
            }else{
                $jobs = $query->where('type','fixed');
            }

            if(filled($request->job_search_string)){
                $jobs = $jobs->WhereHas('job_creator')->where('title', 'LIKE', '%' .strip_tags($request->job_search_string). '%');
            }

            if(isset($request->country) && !empty($request->country)){
                $jobs = $jobs->WhereHas('job_creator',function($q) use($request){
                    $q->where('country_id',$request->country);
                });
            }
            if(isset($request->type) && !empty($request->type)){
                $jobs = $jobs->where('type',$request->type);
            }
            if(isset($request->level) && !empty($request->level)){
                $jobs = $jobs->WhereHas('job_creator',function($q) use($request){
                    $q->where('level',$request->level);
                });
            }
            if(isset($request->min_price) && isset($request->max_price)  && !empty($request->min_price) && !empty($request->max_price)){
                $jobs = $jobs->whereBetween('budget',[$request->min_price,$request->max_price]);
            }
            if(isset($request->duration) && !empty($request->duration)){
                $jobs = $jobs->where('duration',$request->duration);
            }

            $jobs = $jobs->paginate(10);
            return $jobs->total() >= 1 ? view('frontend.pages.category-jobs.search-job-result', compact('jobs'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    public function pagination(Request $request)
    {
        if($request->ajax()){
            $query = JobPost::with('job_creator','job_skills')
                ->whereHas('job_creator')
                ->where('on_off','1')
                ->where('status','1')
                ->withCount('job_proposals')
                ->where('job_approve_request','1')
                ->where('category',$request->category);

            if(moduleExists('HourlyJob')){
                $jobs = $query;
            }else{
                $jobs = $query->where('type','fixed');
            }

            if($request->country == '' && $request->type == '' && $request->level == '' && $request->min_price == '' && $request->max_price == '' && $request->duration == '' && $request->job_search_string == '')
            {
                $jobs = $jobs;
            }
            else
            {
                if(filled($request->job_search_string)){
                    $jobs = $jobs->WhereHas('job_creator')->where('title', 'LIKE', '%' .strip_tags($request->job_search_string). '%');
                }

                if(isset($request->country) && !empty($request->country)){
                    $jobs = $jobs->WhereHas('job_creator',function($q) use($request){
                        $q->where('country_id',$request->country);
                    });
                }
                if(isset($request->type) && !empty($request->type)){
                    $jobs = $jobs->where('type',$request->type);
                }
                if(isset($request->level) && !empty($request->level)){
                    $jobs = $jobs->WhereHas('job_creator',function($q) use($request){
                        $q->where('level',$request->level);
                    });
                }
                if(isset($request->min_price) && isset($request->max_price)  && !empty($request->min_price) && !empty($request->max_price)){
                    $jobs = $jobs->whereBetween('budget',[$request->min_price,$request->max_price]);
                }
                if(isset($request->duration) && !empty($request->duration)){
                    $jobs = $jobs->where('duration',$request->duration);
                }
            }
            $jobs = $jobs->paginate(10);
            return $jobs->total() >= 1 ? view('frontend.pages.category-jobs.search-job-result', compact('jobs'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //reset jobs filter
    public function reset(Request $request)
    {
        $query = JobPost::with('job_creator','job_skills')
            ->whereHas('job_creator')
            ->where('on_off','1')
            ->where('status','1')
            ->withCount('job_proposals')
            ->where('job_approve_request','1')
            ->where('category',$request->category)
            ->latest();

        if(moduleExists('HourlyJob')){
            $jobs = $query->paginate(10);
        }else{
            $jobs = $query->where('type','fixed')->paginate(10);
        }
        return $jobs->total() >= 1 ? view('frontend.pages.category-jobs.search-job-result',compact('jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }
}
