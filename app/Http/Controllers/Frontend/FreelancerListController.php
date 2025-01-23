<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\FreelancerLevel\Entities\FreelancerLevelRules;
use Illuminate\Support\Carbon;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;

class FreelancerListController extends Controller
{
    private $current_date;
    public function __construct()
    {
        $this->current_date = \Carbon\Carbon::now()->toDateTimeString();
    }

    //all talents
    public function talents()
    {
        $talents = User::select(['id', 'username','first_name','last_name','image','country_id','state_id','user_verified_status','load_from'])
            ->with('user_introduction','freelancer_category')
            ->where('user_type','2')
            ->where('is_email_verified',1)
            ->where('is_suspend',0)
            ->withCount(['freelancer_orders' => function ($query) {
                $query->where('status',3);
            }])->orderBy('freelancer_orders_count', 'DESC')
            ->paginate(9);
        return view('frontend.pages.talent.talents',compact(['talents']));
    }

    public function pagination(Request $request)
    {
        if($request->ajax()){
            $is_pro = $request->get_pro_profiles ?? 0;
            $talents = $this->filter_query($request)->paginate(9);
            $talents = $request->country === '' && $request->level === '' && $request->category === ''  && $request->subcategory === '' && $request->talent_badge === '' && $request->skill === '' ?  $talents : $this->filter_query($request)->paginate(9);

            //pro profile impression count
            if(moduleExists('PromoteFreelancer')){
                if($talents->total() >=1 && $is_pro == 1) {
                    foreach ($talents as $talent) {
                        $find_package = PromotionProjectList::where('identity',$talent->id)
                            ->where('type','profile')
                            ->where('expire_date','>=',$this->current_date)
                            ->first();
                        if($find_package){
                            PromotionProjectList::where('id',$find_package->id)->update(['impression'=>$find_package->impression + 1]);
                        }
                    }
                }
            }
            return $talents->total() >= 1 ? view('frontend.pages.talent.search-talent-result', compact(['talents','is_pro']))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //talents filter
    public function talents_filter(Request $request)
    {
        if($request->ajax()){
            $talents = $this->filter_query($request)->paginate(9);
            return $talents->total() >= 1 ? view('frontend.pages.talent.search-talent-result', compact('talents'))->render() : response()->json(['status'=>__('nothing')]);
        }
    }

    //filter query
    private function filter_query($request)
    {
        if($request->get_pro_profiles == 1){
            $talents = User::query()->select('id', 'username','first_name','last_name','image','country_id','state_id','is_pro','pro_expire_date','load_from')
                ->with('user_introduction')
                ->where('user_type','2')
                ->where('is_email_verified',1)
                ->where('is_suspend',0)
                ->where('pro_expire_date','>',$this->current_date)
                ->where('is_pro','yes')
                ->withCount(['freelancer_orders' => function ($query) {
                    $query->where('status',3);
                }])
                ->withSum(['freelancer_orders' => function ($query) {
                    $query->where('status',3);
                }],'payable_amount')
                ->withAvg(['freelancer_ratings' => function ($query) {
                    $query->where('sender_type',1);
                }],'rating')
                ->inRandomOrder();
        }else{
            $talents = User::query()->select('id', 'username','first_name','last_name','image','country_id','state_id','is_pro','pro_expire_date','load_from')
                ->with('user_introduction')
                ->where('user_type','2')
                ->where('is_email_verified',1)
                ->where('is_suspend',0)
                ->withCount(['freelancer_orders' => function ($query) {
                    $query->where('status',3);
                }])
                ->withSum(['freelancer_orders' => function ($query) {
                    $query->where('status',3);
                }],'payable_amount')
                ->withAvg(['freelancer_ratings' => function ($query) {
                    $query->where('sender_type',1);
                }],'rating')

                ->orderBy('freelancer_orders_count', 'DESC');
        }

        if(!empty($request->country)){
            $talents = $talents->where('country_id',$request->country);
        }

        if(!empty($request->level)){
            $talents = $talents->where('experience_level',$request->level);
        }

        if(!empty($request->category)){
            $talents = $talents->whereHas('freelancer_category', function($q) use ($request){
                $q->where('category_id',$request->category);
            });
        }

        if(!empty($request->subcategory)){
            $talents = $talents->whereHas('freelancer_subcategory', function($q) use ($request){
                $q->whereIn('sub_category_id',$request->subcategory);
            });
        }


        if(!empty($request->talent_badge)){
            $rule = FreelancerLevelRules::where('period',$request->talent_badge)->first();

            if ($request->talent_badge >= 1 && $request->talent_badge < 3){
                $talents = $talents
                    ->whereDate('created_at', '>=', now()->subDays(90)) // Created within the last 90 days
                    ->whereDate('created_at', '<', now()->subDays(30))  // Created at least 30 days ago
                    ->whereHas('freelancer_orders', function ($q) use ($rule) {
                        $q->havingRaw('COUNT(*) >= ?', [$rule->complete_order]) // Ensure completed order count meets rule
                        ->havingRaw('SUM(payable_amount) >= ?', [$rule->earning]); // Ensure total earnings meet rule
                    })
                    ->whereHas('freelancer_ratings', function ($q) use ($rule) {
                        $q->havingRaw('AVG(rating) >= ?', [$rule->avg_rating]);
                    });
            }
            elseif($request->talent_badge >= 3 && $request->talent_badge < 6){
                $talents = $talents
                    ->whereDate('created_at', '>=', now()->subDays(180)) // Created within the last 180 days
                    ->whereDate('created_at', '<', now()->subDays(90))  // Created at least 90 days ago
                    ->whereHas('freelancer_orders', function ($q) use ($rule) {
                        $q->havingRaw('COUNT(*) >= ?', [$rule->complete_order])
                        ->havingRaw('SUM(payable_amount) >= ?', [$rule->earning]);
                    })
                    ->whereHas('freelancer_ratings', function ($q) use ($rule) {
                            $q->havingRaw('AVG(rating) >= ?', [$rule->avg_rating]);
                    });
            }
            elseif($request->talent_badge >= 6 && $request->talent_badge < 9){
                $talents = $talents
                    ->whereDate('created_at', '>=', now()->subDays(270)) // Created within the last 180 days
                    ->whereDate('created_at', '<', now()->subDays(180))  // Created at least 90 days ago
                    ->whereHas('freelancer_orders', function ($q) use ($rule) {
                        $q->havingRaw('COUNT(*) >= ?', [$rule->complete_order]) // Ensure completed order count meets rule
                        ->havingRaw('SUM(payable_amount) >= ?', [$rule->earning]); // Ensure total earnings meet rule
                    })
                    ->whereHas('freelancer_ratings', function ($q) use ($rule) {
                        $q->havingRaw('AVG(rating) >= ?', [$rule->avg_rating]);
                    });
            }
            elseif($request->talent_badge >= 9 && $request->talent_badge < 12){
                $talents = $talents
                    ->whereDate('created_at', '>=', now()->subDays(360)) // Created within the last 180 days
                    ->whereDate('created_at', '<', now()->subDays(270))  // Created at least 90 days ago
                    ->whereHas('freelancer_orders', function ($q) use ($rule) {
                        $q->havingRaw('COUNT(*) >= ?', [$rule->complete_order]) // Ensure completed order count meets rule
                        ->havingRaw('SUM(payable_amount) >= ?', [$rule->earning]); // Ensure total earnings meet rule
                    })
                    ->whereHas('freelancer_ratings', function ($q) use ($rule) {
                        $q->havingRaw('AVG(rating) >= ?', [$rule->avg_rating]);
                    });
            }

            elseif($request->talent_badge >= 12){
                $talents = $talents
                    ->whereDate('created_at', '>=', now()->subDays(360)) // Created within the last 180 days
                    ->whereHas('freelancer_orders', function ($q) use ($rule) {
                        $q->havingRaw('COUNT(*) >= ?', [$rule->complete_order]) // Ensure completed order count meets rule
                        ->havingRaw('SUM(payable_amount) >= ?', [$rule->earning]); // Ensure total earnings meet rule
                    })
                    ->whereHas('freelancer_ratings', function ($q) use ($rule) {
                        $q->havingRaw('AVG(rating) >= ?', [$rule->avg_rating]);
                    });
            }
        }

        if(!empty($request->skill)){
            $talents = $talents->whereHas('freelancer_skill', function($q) use ($request){
                $q->where('skill', 'LIKE', '%' .$request->skill. '%');
            });
        }

        return $talents;
    }

    //filter reset
    public function reset()
    {
        $talents = User::select(['id', 'username','first_name','last_name','image','country_id','state_id','load_from'])
            ->with('user_introduction','freelancer_category')
            ->where('user_type','2')
            ->where('is_email_verified',1)
            ->where('is_suspend',0)
            ->withCount(['freelancer_orders' => function ($query) {
                $query->where('status',3);
            }])->orderBy('freelancer_orders_count', 'DESC')
            ->paginate(9);
        return $talents->total() >= 1 ? view('frontend.pages.talent.search-talent-result',compact('talents'))->render() : response()->json(['status'=>__('nothing')]);
    }

}
