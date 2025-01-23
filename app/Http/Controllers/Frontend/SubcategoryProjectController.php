<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;
use Modules\Service\Entities\SubCategory;

class SubcategoryProjectController extends Controller
{
    public function __construct()
    {
        $this->current_date = \Carbon\Carbon::now()->toDateTimeString();
    }

    public function sub_category_projects($slug)
    {
        $is_pro = 0;
        $subcategory = SubCategory::select('id','sub_category','meta_title','meta_description')->where('slug',$slug)->first();
        if(!empty($subcategory)){
            $projects = $subcategory->projects()
                ->with('project_creator')
                ->whereHas('project_creator')
                ->where('project_on_off','1')
                ->where('status','1')
                ->latest()
                ->paginate(10);
            return view('frontend.pages.subcategory-projects.projects',compact('subcategory','projects','is_pro'));
        }
        return back();
    }

    public function sub_category_project_filter(Request $request)
    {
        if($request->ajax()){
            $subcategory = SubCategory::select('id','sub_category')->where('id',$request->subcategory_id)->first();
            $projects = $subcategory->projects()
                ->with('project_creator')
                ->where('project_on_off','1')
                ->latest()
                ->where('status','1');

            if(filled($request->job_search_string)){
                $projects = $projects->WhereHas('project_creator')->where('title', 'LIKE', '%' .strip_tags($request->job_search_string). '%');
            }

            if(!empty($request->country)){
                $projects = $projects->WhereHas('project_creator',function($q) use($request){
                    $q->where('country_id',$request->country);
                });
            }

            if(!empty($request->level)){
                $projects = $projects->WhereHas('project_creator',function($q) use($request){
                    $q->where('experience_level',$request->level);
                });
            }

            if(!empty($request->min_price) && !empty($request->max_price)){
                $projects = $projects->whereBetween('basic_regular_charge',[$request->min_price,$request->max_price]);
            }

            if(!empty($request->delivery_day)){
                $projects = $projects->where('basic_delivery',$request->delivery_day);
            }

            if(!empty($request->rating)){
                $projects = $projects->withAvg(['ratings' => function ($query){
                    $query->where('sender_id', 1);
                }],'rating')
                    ->having('ratings_avg_rating',">", $request->rating -1)
                    ->having('ratings_avg_rating',"<=", $request->rating);
            }

            $projects = $projects->paginate(10);
            return $projects->total() >= 1 ? view('frontend.pages.subcategory-projects.search-subcategory-result', compact('projects'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    public function pagination(Request $request)
    {
        if($request->ajax()){
            $is_pro = $request->get_pro_projects ?? 0;
            $subcategory = SubCategory::select('id','sub_category')->where('id',$request->subcategory_id)->first();

            if($request->get_pro_projects == 1){
                $projects = $subcategory->projects()
                    ->with('project_creator')
                    ->where('project_on_off','1')
                    ->where('status','1')
                    ->where('pro_expire_date','>',$this->current_date)
                    ->where('is_pro','yes')
                    ->inRandomOrder();
            }else{
                $projects = $subcategory->projects()
                    ->with('project_creator')
                    ->where('project_on_off','1')
                    ->where('status','1')
                    ->latest();
            }

            if(filled($request->job_search_string)){
                $projects = $projects->WhereHas('project_creator')->where('title', 'LIKE', '%' .strip_tags($request->job_search_string). '%');
            }

            if (isset($request->country) && !empty($request->country)) {
                $projects = $projects->WhereHas('project_creator', function ($q) use ($request) {
                    $q->where('country_id', $request->country);
                });
            }

            if (isset($request->level) && !empty($request->level)) {
                $projects = $projects->WhereHas('project_creator', function ($q) use ($request) {
                    $q->where('experience_level', $request->level);
                });
            }

            if (isset($request->min_price) && isset($request->max_price) && !empty($request->min_price) && !empty($request->max_price)) {
                $projects = $projects->whereBetween('basic_regular_charge', [$request->min_price, $request->max_price]);
            }

            if (isset($request->delivery_day) && !empty($request->delivery_day)) {
                $projects = $projects->where('basic_delivery', $request->delivery_day);
            }

            if(!empty($request->rating)){
                $projects = $projects->withAvg(['ratings' => function ($query){
                    $query->where('sender_type', 1);
                }],'rating')
                    ->having('ratings_avg_rating',">", $request->rating -1)
                    ->having('ratings_avg_rating',"<=", $request->rating);
            }

            $projects = $projects->paginate(10);

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
            return $projects->total() >= 1 ? view('frontend.pages.subcategory-projects.search-subcategory-result', compact('projects','is_pro'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //reset jobs filter
    public function reset(Request $request)
    {
        $subcategory = SubCategory::select('id','sub_category')->where('id',$request->subcategory_id)->first();
        $projects = $subcategory->projects()
            ->with('project_creator')
            ->where('project_on_off','1')
            ->where('status','1')
            ->latest()
            ->paginate(10);
        return $projects->total() >= 1 ? view('frontend.pages.subcategory-projects.search-subcategory-result',compact('projects'))->render() : response()->json(['status'=>__('nothing')]);
    }
}
