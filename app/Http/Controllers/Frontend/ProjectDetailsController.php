<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;
use Modules\Subscription\Http\Controllers\Frontend\FrontendSubscriptionController;

class ProjectDetailsController extends Controller
{
    public function __construct(private FrontendJobsController $jobsController, private FrontendSubscriptionController $subscriptionController)
    {
     //
    }

    //project details
    public function project_details($username,$slug=null)
    {
        if($username == 'jobs' && $slug == "all"){
            //: now call frontendjobscontroller method jobs for getting all content
            return $this->jobsController->jobs();
        }

        if($username == 'subscriptions' && $slug == "all"){
            //: now call FrontendSubscriptionController method subscriptions for getting all content
            return $this->subscriptionController->subscriptions();
        }

        if($slug != 'admin'){
            $project = Project::where('slug',$slug)->first();
            if(!empty($project)){
                $user = User::with('user_introduction','user_country','user_state','user_city')->where('id',$project->user_id)->first();
                $project_complete_orders = Order::select('id','identity','status','is_project_job')->where('identity',$project->id)
                    ->whereHas('rating')
                    ->where('status',3)
                    ->where('is_project_job','project')
                    ->paginate(10);

                if(!$user){ abort(404);}
                //pro project view count
                if(moduleExists('PromoteFreelancer')) {
                    if (Session::has('is_pro')) {
                        $current_date = \Carbon\Carbon::now()->toDateTimeString();
                        $find_package = PromotionProjectList::where('identity', $project->id)
                            ->where('type', 'project')
                            ->where('expire_date', '>=', $current_date)
                            ->first();
                        if ($find_package) {
                            PromotionProjectList::where('id', $find_package->id)->update(['click' => $find_package->click + 1]);
                            Session::forget('is_pro');
                        }
                    }
                }
            }else{ return back(); }
            return  view('frontend.pages.project-details.project-details',compact(['project','user','project_complete_orders']));
        }else{
            return view('backend.pages.auth.login');
        }
    }

    //load more review
    public function load_more_review(Request $request)
    {
        $project_id = $request->project_id;
        $project_complete_orders = Order::select('id','identity','status','is_project_job')->where('identity',$project_id)
            ->whereHas('rating')
            ->where('status',3)
            ->where('is_project_job','project')
            ->paginate(10);
        return view('frontend.pages.project-details.reviews', compact(['project_complete_orders','project_id']))->render();
    }
}
