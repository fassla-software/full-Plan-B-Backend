<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;

class FrontendProjectsController extends Controller
{
    private $current_date;
    public function __construct()
    {
        $this->current_date = \Carbon\Carbon::now()->toDateTimeString();
    }
    //all projects
    public function projects(Request $request)
    {
        $projects = $this->common_query($request)->paginate(10);
        $is_pro = 0;
        return view('frontend.pages.projects.projects',compact(['projects','is_pro']));
    }

    public function pro_projects(Request $request)
    {
       $projects = $this->common_query($request)->paginate(10);
        return view('frontend.pages.projects.search-result',compact('projects'))->render();
    }

    //projects filter
    public function projects_filter(Request $request)
    {
        if($request->ajax()){
            $projects = $this->filter_query($request)->paginate(10);
            return $projects->total() >= 1 ? view('frontend.pages.projects.search-result', compact('projects'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //projects pagination
    public function pagination(Request $request)
    {
        if($request->ajax()){
            $is_pro = $request->get_pro_projects ?? 0;
            $projects =  $this->filter_query($request)->paginate(10);

            //pro project impression count
            if(moduleExists('PromoteFreelancer')){
                if($projects->total() >=1 && $is_pro == 1) {
                    foreach ($projects as $project) {
                        $find_package = PromotionProjectList::where('identity',$project->id)
                            ->where('type','project')
                            ->where('expire_date','>=',$this->current_date)
                            ->first();
                        if($find_package){
                            PromotionProjectList::where('id',$find_package->id)->update(['impression'=>$find_package->impression + 1]);
                        }
                    }
                }
            }

            return $projects->total() >= 1 ? view('frontend.pages.projects.search-result', compact(['projects','is_pro']))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //reset projects filter
    public function reset(Request $request)
    {
        $projects = $this->common_query($request)->paginate(10);
        return $projects->total() >= 1 ? view('frontend.pages.projects.search-result',compact('projects'))->render() : response()->json(['status'=>__('nothing')]);
    }

   //common query
    private function common_query($request)
    {
        if($request->get_pro_projects == 1){
            return Project::query()->with('project_creator')
                ->whereHas('project_creator')
                ->select(['id', 'title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image','pro_expire_date','is_pro','load_from'])
                ->where('project_on_off','1')
                ->where('pro_expire_date','>',$this->current_date)
                ->where('is_pro','yes')
                ->where('status','1')
                ->inRandomOrder();
        }
        else{
            return Project::query()->with('project_creator')
                ->whereHas('project_creator')
                ->select(['id', 'title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image','pro_expire_date','is_pro','load_from'])
                ->where('project_on_off','1')
                ->latest()
                ->where('status','1');
        }
    }

    //filter query
    private function filter_query($request)
    {
        $query = $this->common_query($request);

        if(filled($request->job_search_string)){
            $query->WhereHas('project_creator')->where('title', 'LIKE', '%' .strip_tags($request->job_search_string). '%');
        }

        if(!empty($request->category)){
            $query = $query->where('category_id',$request->category);
        }

        if(!empty($request->subcategory)){
            $query = $query->whereHas('project_sub_categories', function ($q) use ($request) {
                $q->whereIn('sub_categories.id', $request->subcategory);
            });
        }

        if(!empty($request->country)){
            $query = $query->whereHas('project_creator',function($q) use($request){
                $q->where('country_id',$request->country);
            });
        }

        if(!empty($request->level)){
            $query = $query->whereHas('project_creator',function($q) use($request){
                $q->where('experience_level',$request->level);
            });
        }

        if(!empty($request->min_price) && !empty($request->max_price)){
            $query = $query->whereBetween('basic_regular_charge',[$request->min_price,$request->max_price]);
        }

        if(!empty($request->delivery_day)){
            $query = $query->where('basic_delivery',$request->delivery_day);
        }

        if(!empty($request->rating)){
            $query = $query->withAvg(['ratings' => function ($query){
                $query->where('sender_type', 1);
            }],'rating')
            ->having('ratings_avg_rating',">", $request->rating -1)
            ->having('ratings_avg_rating',"<=", $request->rating);
        }

        return $query;
    }

}
