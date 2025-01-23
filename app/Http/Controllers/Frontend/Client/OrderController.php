<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Models\IndividualCommissionSetting;
use App\Models\JobPost;
use App\Models\Order;
use App\Models\OrderMilestone;
use App\Models\OrderRequestRevision;
use App\Models\OrderSubmitHistory;
use App\Models\OrderWorkHistory;
use App\Models\Project;
use App\Models\Rating;
use App\Models\RatingDetails;
use App\Models\Report;
use App\Models\UserEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Entities\Wallet;


class OrderController extends Controller
{
    //client all order
    public function all_orders()
    {
        $client_id = Auth::guard('web')->user()->id;

        if(get_static_option('project_enable_disable') != 'disable'){

            $orders = Order::where('user_id', $client_id)
                ->where(function ($query) {
                    $query->whereHas('freelancer', function ($q) {
                        $q->where('payment_status', 'complete');
                    })
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereHas('freelancer')->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                })
                ->latest()
                ->paginate(10);


            $queue_orders = Order::where('user_id', $client_id)
                ->whereHas('freelancer')
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('status', 0)
                            ->where('payment_status', 'complete');
                    })
                        ->orWhere(function ($q) {
                            $q->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                })
                ->count();

            $active_orders = Order::where('user_id',$client_id)->whereHas('freelancer')->where('status',1)->count();
            $complete_orders = Order::where('user_id',$client_id)->whereHas('freelancer')->where('status',3)->count();
            $cancel_orders = Order::where('user_id',$client_id)->whereHas('freelancer')->where('status',4)->count();
        }else{
            $orders = Order::where('user_id', $client_id)
                ->where('is_project_job', '!=', 'project')
                ->whereHas('freelancer')
                ->where(function ($query) {
                    $query->where('payment_status', 'complete')
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                })
                ->latest()
                ->paginate(10);


            $queue_orders = Order::where('user_id', $client_id)
                ->where('is_project_job', '!=', 'project')
                ->whereHas('freelancer')
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('status', 0)
                            ->where('payment_status', 'complete');
                    })
                        ->orWhere(function ($q) {
                            $q->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                })
                ->count();

            $active_orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->whereHas('freelancer')->where('status',1)->count();
            $complete_orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->whereHas('freelancer')->where('status',3)->count();
            $cancel_orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->whereHas('freelancer')->where('status',4)->count();
        }

        $top_projects = Project::select('id', 'title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image')
            ->whereHas('project_creator')
            ->where('project_on_off','1')
            ->where('status','1')
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.user.client.order.orders',compact(['orders','queue_orders','active_orders','complete_orders','cancel_orders','top_projects']));
    }

    // sort
    public function sort_by(Request $request)
    {
        $client_id = Auth::guard('web')->user()->id;

        if(get_static_option('project_enable_disable') != 'disable'){
            if($request->order_type == 'all')
            {
                $orders = Order::where('user_id', $client_id)
                    ->where(function ($query) {
                        $query->where('payment_status', 'complete')
                            ->orWhere(function ($subQuery) {
                                $subQuery->where('payment_gateway', 'manual_payment')
                                    ->whereIn('payment_status', ['pending', 'complete']);
                            });
                    })
                    ->latest()
                    ->paginate(10);
            }
            if($request->order_type == 'active')
            {
                $orders = Order::where('user_id',$client_id)->latest()->where('status',1)->paginate(10);
            }
            if($request->order_type == 'queue')
            {
                $orders = Order::where('user_id', $client_id)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->where('status', 0)
                                ->where('payment_status', 'complete');
                        })->orWhere(function ($q) {
                            $q->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                    })
                    ->latest()
                    ->paginate(10);
            }
            if($request->order_type == 'cancel')
            {
                $orders = Order::where('user_id',$client_id)->latest()->where('status',4)->paginate(10);
            }
            if($request->order_type == 'complete')
            {
                $orders = Order::where('user_id',$client_id)->latest()->where('status',3)->paginate(10);
            }
        }else{
            if($request->order_type == 'all')
            {
                $orders = Order::where('user_id', $client_id)->where('is_project_job', '!=', 'project')
                    ->where(function ($query) {
                        $query->where('payment_status', 'complete')
                            ->orWhere(function ($subQuery) {
                                $subQuery->where('payment_gateway', 'manual_payment')
                                    ->whereIn('payment_status', ['pending', 'complete']);
                            });
                    })
                    ->latest()
                    ->paginate(10);
            }
            if($request->order_type == 'active')
            {
                $orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->latest()->where('status',1)->paginate(10);
            }
            if($request->order_type == 'queue')
            {
                $orders = Order::where('user_id', $client_id)->where('is_project_job', '!=', 'project')
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->where('status', 0)
                                ->where('payment_status', 'complete');
                        })->orWhere(function ($q) {
                            $q->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                    })
                    ->latest()
                    ->paginate(10);
            }
            if($request->order_type == 'cancel')
            {
                $orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->latest()->where('status',4)->paginate(10);
            }
            if($request->order_type == 'complete')
            {
                $orders = Order::where('user_id',$client_id)->where('is_project_job', '!=', 'project')->latest()->where('status',3)->paginate(10);
            }
        }
        return view('frontend.user.client.order.search-result', compact('orders'))->render();
    }

    // pagination
    public function pagination(Request $request)
    {
        if($request->ajax()){
            $client_id = Auth::guard('web')->user()->id;
            $query = Order::where('user_id',$client_id)->latest();

            if(get_static_option('project_enable_disable') != 'disable'){
                if($request->order_type == 'all')
                {
                    $orders = Order::where(function ($query) use ($client_id) {
                        $query->where('user_id', $client_id)->where('payment_status', 'complete');
                        $query->orWhere(function ($query) use ($client_id) {
                            $query->where('user_id', $client_id);
                            $query->where('payment_gateway', 'manual_payment');
                            $query->whereIn('payment_status', ['pending', 'complete']);
                        });
                    })->latest()->paginate(10);

                }
                if($request->order_type == 'active')
                {
                    $orders = $query->where('status',1)->paginate(10);
                }
                if($request->order_type == 'queue')
                {
                    $orders = Order::where(function ($query) use ($client_id) {
                        // Apply the user_id, status, and payment_status conditions
                        $query->where('user_id', $client_id)
                            ->where('status', 0)
                            ->where('payment_status', 'complete');
                    })
                        ->orWhere(function ($query) {
                            // Apply the payment_gateway and payment_status conditions
                            $query->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        })
                        ->latest()
                        ->paginate(10);
                }
                if($request->order_type == 'cancel')
                {
                    $orders = $query->where('status',4)->paginate(10);
                }
                if($request->order_type == 'complete')
                {
                    $orders = $query->where('status',3)->paginate(10);
                }
            }else{
                if($request->order_type == 'all')
                {
                    $orders = Order::where('is_project_job', '!=', 'project')
                        ->where(function ($query) use ($client_id) {
                            // Nested condition for user_id with complete payment status
                            $query->where(function ($subQuery) use ($client_id) {
                                $subQuery->where('user_id', $client_id)
                                    ->where('payment_status', 'complete');
                            })
                                // Additional nested condition for the same user_id but with manual payment gateway
                                // and either pending or complete payment status
                                ->orWhere(function ($subQuery) use ($client_id) {
                                    $subQuery->where('user_id', $client_id)
                                        ->where('payment_gateway', 'manual_payment')
                                        ->whereIn('payment_status', ['pending', 'complete']);
                                });
                        })
                        ->latest()
                        ->paginate(10);

                }
                if($request->order_type == 'active')
                {
                    $orders = $query->where('is_project_job', '!=', 'project')->where('status',1)->paginate(10);
                }
                if($request->order_type == 'queue')
                {
                    $orders = Order::where('is_project_job', '!=', 'project')
                        ->where(function ($query) use ($client_id) {
                            // Apply user_id, status, and payment_status conditions within the non-project scope
                            $query->where(function ($subQuery) use ($client_id) {
                                $subQuery->where('user_id', $client_id)
                                    ->where('status', 0)
                                    ->where('payment_status', 'complete');
                            })
                                // Include orWhere condition for manual payment within the same scope
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('payment_gateway', 'manual_payment')
                                        ->whereIn('payment_status', ['pending', 'complete']);
                                });
                        })
                        ->latest()
                        ->paginate(10);
                }
                if($request->order_type == 'cancel')
                {
                    $orders = $query->where('is_project_job', '!=', 'project')->where('status',4)->paginate(10);
                }
                if($request->order_type == 'complete')
                {
                    $orders = $query->where('is_project_job', '!=', 'project')->where('status',3)->paginate(10);
                }
            }

            return view('frontend.user.client.order.search-result', compact('orders'))->render();
        }
    }

    //order details
    public function order_details($id)
    {
        $client_id = Auth::guard('web')->user()->id;
        $order_details = Order::with(['freelancer:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,user_verified_status','order_submit_history'])
            ->where('id',$id)
            ->where('user_id',$client_id)
            ->first();
        return !empty($order_details) ? view('frontend.user.client.order.order-details',compact('order_details')) : back();
    }

    //active order milestones
    public function active_milestone($id)
    {
       OrderMilestone::where('id',$id)->update(['status' => 1]);
       return back()->with(toastr_success(__('Milestone successfully updated')));
    }

    //create revision
    public function request_revision(Request $request)
    {
        $msg = __("You can't send revision request because your revision limit has been finished");
        if($request->order_milestone_id){
            $revision_left = OrderMilestone::select('id','revision_left')->where('id',$request->order_milestone_id)->first();
            if($revision_left->revision_left > 0){
                $this->create_request_revision($request->order_id, $request->order_milestone_id, $request->order_submit_history_id, $request->revision_description);

                OrderMilestone::where('id',$request->order_milestone_id)->update(['status'=>1]);
                OrderMilestone::find($request->order_milestone_id)->decrement('revision_left');
                OrderSubmitHistory::where('order_milestone_id',$request->order_milestone_id)->update(['status'=>2]);
            }else{
                return back()->with(toastr_warning($msg));
            }
        }else{
            $revision_left = Order::select('id','revision_left')->where('id',$request->order_id)->first();
            if($revision_left->revision_left > 0){
                $this->create_request_revision($request->order_id, $request->order_milestone_id, $request->order_submit_history_id, $request->revision_description);

                Order::find($request->order_id)->decrement('revision_left');
                Order::where('id',$request->order_id)->update(['status'=>1]);
                OrderSubmitHistory::where('order_id',$request->order_id)->update(['status'=>2]);
            }else{
                return back()->with(toastr_warning($msg));
            }
        }

        //order revision request
        $freelancer = Order::select('id','freelancer_id')->where('id',$request->order_id)->first();
        freelancer_notification($request->order_id, $freelancer->freelancer_id, 'Order',__('Request for revision'));

        return back()->with(toastr_success(__('Revision request successfully created')));
    }

    //approve order and revision
    public function order_milestone_approve($id, $type)
    {
        if($type == 'milestone'){
            $milestone = OrderMilestone::where('id',$id)->first();
            $freelancer_id = Order::select('freelancer_id')->where('id',$milestone->order_id)->first();
            $total_earning = UserEarning::where('user_id',$freelancer_id->freelancer_id)->first();

            if($total_earning){
                //update if freelancer has any earnings
                UserEarning::where('user_id',$freelancer_id->freelancer_id)->update([
                    'total_earning'=>$total_earning->total_earning + $milestone->price,
                    'remaining_balance'=> ($total_earning->total_earning+$milestone->price) - $total_earning->total_withdraw
                ]);
            }else {
                //Create if freelancer has no earnings
                UserEarning::create([
                    'user_id' => $freelancer_id->freelancer_id,
                    'total_earning' => $milestone->price,
                    'remaining_balance' => $milestone->price
                ]);
            }

            //update freelancer wallet balance
            $freelancer_wallet = Wallet::where('user_id',$freelancer_id->freelancer_id)->first();
            Wallet::where('user_id',$freelancer_id->freelancer_id)->update([
                'balance'=>$freelancer_wallet->balance + $milestone->price,
                'remaining_balance'=> $freelancer_wallet->remaining_balance+$milestone->price
            ]);

            //complete single milestone
            OrderMilestone::where('id',$milestone->id)->update(['status'=>2]);

            //approve submitted work
            $order_submit_history = OrderSubmitHistory::where('order_milestone_id',$milestone->id)->OrderBy('id','DESC')->first();
            OrderSubmitHistory::where('id',$order_submit_history->id)->update(['status'=>1]);

            //active next milestone
            $next_milestone = OrderMilestone::where('order_id',$milestone->order_id)->where('id', '>', $milestone->id)->min('id');

            //freelancer and admin notification
            freelancer_notification($milestone->order_id, $freelancer_id->freelancer_id,'Order',__('Order accepted by client'));
            notificationToAdmin($milestone->order_id, Auth::user()->id,'Order',__('Order accepted by client'));

            if($next_milestone){
                OrderMilestone::where('id',$next_milestone)->update(['status'=>1]);
                return back()->with(toastr_success(__('Payment Successfully Completed')));
            }else{
                //update order status complete after complete all milestones
                Order::where('id',$milestone->order_id)->update(['status'=>3]);
                toastr_success(__('Payment Successfully Completed'));
                return redirect()->route('client.order.rating',$id);
            }

        }else{
              $order = Order::where('id',$id)->first();
              $total_earning = UserEarning::where('user_id',$order->freelancer_id)->first();
              $client_wallet = Wallet::where('user_id',Auth::guard('web')->user()->id)->first();
              $freelancer_wallet = Wallet::where('user_id',$order->freelancer_id)->first();

            if($total_earning){
                //update total earning if freelancer has any earnings
                UserEarning::where('user_id',$order->freelancer_id)->update([
                    'total_earning'=>$total_earning->total_earning + $order->payable_amount,
                    'remaining_balance'=> ($total_earning->total_earning+$order->payable_amount) - $total_earning->total_withdraw
                ]);
            }else{
                //Create total earning if freelancer has no earnings
                UserEarning::create([
                    'user_id'=>$order->freelancer_id,
                    'total_earning'=>$order->payable_amount,
                    'remaining_balance'=>$order->payable_amount
                ]);
            }

            //this code is only for hourly job module
            if($order->is_fixed_hourly == 'hourly'){

                $commission_type = get_static_option('admin_commission_type') ?? 'percentage';
                $commission_charge = get_static_option('admin_commission_charge') ?? 25;

                $order_total_seconds = OrderWorkHistory::where('order_id',$order->id)->where('client_id',Auth::guard('web')->user()->id)
                    ->sum('seconds');
                $order_total_amount_calculate = ($order_total_seconds / 3600) * $order?->job->hourly_rate;
                $amount = number_format($order_total_amount_calculate,2);

                $individual_commission = IndividualCommissionSetting::select(['user_id','admin_commission_type','admin_commission_charge'])->where('user_id',$order->freelancer_id)->first();
                $commission_amount = commission_amount($amount,$individual_commission,$commission_type,$commission_charge);

                if($client_wallet->balance >= $amount){
                    //update freelancer wallet balance
                    Wallet::where('user_id',$order->freelancer_id)->update([
                        'balance'=>$freelancer_wallet->balance + ($amount - $commission_amount),
                        'remaining_balance'=> $freelancer_wallet->remaining_balance + ($amount - $commission_amount)
                    ]);

                    //update client wallet balance
                    Wallet::where('user_id',Auth::guard('web')->user()->id)->update([
                        'balance'=>$client_wallet->balance - $amount,
                        'remaining_balance'=> $client_wallet->remaining_balance - $amount
                    ]);

                    //update order
                    Order::where('id',$id)->update([
                        'price'=>$amount,
                        'commission_type'=>$commission_type,
                        'commission_charge'=>$commission_charge,
                        'commission_amount'=>number_format($commission_amount,2),
                        'payable_amount'=>$amount - $commission_amount,
                        'total_hour'=>number_format(($order_total_seconds / 3600),2),
                    ]);
                }else{
                    return back()->with(toastr_warning(__('Your wallet has not enough balance to complete the order. Please deposit first.')));
                }
            }else{
                //update freelancer wallet balance for normal order
                Wallet::where('user_id',$order->freelancer_id)->update([
                    'balance'=>$freelancer_wallet->balance + $order->payable_amount,
                    'remaining_balance'=> $freelancer_wallet->remaining_balance+$order->payable_amount
                ]);
            }

            //update order status to complete
            Order::where('id',$id)->update(['status'=>3]);

            //approve submitted work
            $order_submit_history = OrderSubmitHistory::where('order_id',$id)->OrderBy('id','DESC')->first();
            if($order_submit_history){
                OrderSubmitHistory::where('id',$order_submit_history->id)->update(['status'=>1]);
            }

            //freelancer and admin notification
            freelancer_notification($milestone->order_id ?? $order->id, $order->freelancer_id, 'Order',__('Order accepted by client'));
            notificationToAdmin($milestone->order_id ?? $order->id, Auth::user()->id,'Order',__('Order accepted by client'));

            //if order from job proposal then first find job_id from order and update the job current_status
            $find_order = Order::findOrFail($id);
            if($order && $order->is_project_job == 'job'){
                JobPost::where('id',$find_order->identity)->update(['current_status'=>2]);
            }

            toastr_success(__('Payment Successfully Completed'));
            return redirect()->route('client.order.rating',$id);
        }

    }

    //add rating after approve full order
    public function order_rating(Request $request,$id)
    {
        $query = Order::with('freelancer:id,first_name')->select('id','user_id','freelancer_id','status')->where('id',$id)->where('user_id',auth()->user()->id);
        $find_login_user_order = $query->where(function($q){
            $q->where('status', '=', 3)
              ->orWhere('status', '=', 4);
        })->first();

        if($find_login_user_order){
            if($request->isMethod('post')){
                $skill = $request->skill_rating;
                $availability = $request->availability_rating;
                $communication = $request->communication_rating;
                $work_quality = $request->work_quality_rating;
                $deadline = $request->deadline_rating;
                $co_operation = $request->co_operation_rating;

                $count = 0;
                $count = $request->skill_rating > 0 ? $count + 1 : $count;
                $count = $request->availability_rating > 0 ? $count + 1 : $count;
                $count = $request->communication_rating > 0 ? $count + 1 : $count;
                $count = $request->work_quality_rating > 0 ? $count + 1 : $count;
                $count = $request->deadline_rating > 0 ? $count + 1 : $count;
                $count = $request->co_operation_rating > 0 ? $count + 1 : $count;

                $average_rating = ($skill + $availability+ $communication + $work_quality + $deadline + $co_operation)/$count;

                if($skill < 1 && $availability < 1 && $communication < 1 && $work_quality < 1  && $deadline < 1 && $co_operation < 1){
                    return back()->with(toastr_warning( __('Please select at least one rating item')));
                }

                //check already submit or not review
                $rating = Rating::where('order_id',$find_login_user_order->id)->where('sender_id',$find_login_user_order->user_id)->first();
                if($rating){
                    return back()->with(toastr_warning( __('You have already submitted a review for this order. For a order you can submit only one review.')));
                }
                else{
                    $rating = Rating::create([
                        'order_id'=>$id,
                        'sender_id'=>auth()->user()->id,
                        'sender_type'=>1,
                        'rating'=>round($average_rating,1),
                        'review_feedback'=>$request->review_feedback,
                    ]);

                    for($i=0; $i<=5; $i++){
                        $rating_types = ['skill','availability','communication','work-quality','deadline','co-operation'];
                        $rating_individual = [$skill, $availability, $communication, $work_quality, $deadline, $co_operation];

                        if($rating_individual[$i] > 0) {
                            RatingDetails::create([
                                'rating_id' => $rating->id,
                                'type' => $rating_types[$i],
                                'rating' => $rating_individual[$i],
                            ]);
                        }
                    }
                    return back()->with(toastr_success( __('Rating successfully submitted.')));
                }
            }
            return view('frontend.user.client.order.rating.rating',compact(['id','find_login_user_order']));
        }
        return back();
    }

    //create revision
    private function create_request_revision($order_id,$milestone_id,$history_id,$description)
    {
        OrderRequestRevision::create([
            'order_id'=>$order_id,
            'milestone_id'=>$milestone_id,
            'order_submit_history_id'=>$history_id,
            'description'=>$description,
        ]);
    }

    //report
    public function report(Request $request)
    {
        $request->validate([
            'report_title' => 'required|max:190',
            'report_description' => 'required',
        ]);

        Report::create([
            'order_id' => $request->report_order_id,
            'client_id' => auth()->user()->id,
            'freelancer_id' => $request->report_to_freelancer_id,
            'reporter' => 'client',
            'title' => $request->report_title,
            'description' => $request->report_description,
            'status' => 0
        ]);
        return back()->with(toastr_success(__('Report Successfully Send')));
    }
}

