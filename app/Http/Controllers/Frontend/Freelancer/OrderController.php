<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Order;
use App\Models\OrderDeclineHistory;
use App\Models\OrderDeclineWalletHistory;
use App\Models\OrderMilestone;
use App\Models\OrderSubmitHistory;
use App\Models\OrderWorkHistory;
use App\Models\Rating;
use App\Models\RatingDetails;
use App\Models\Report;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Entities\Wallet;

class OrderController extends Controller
{
    // all order
    public function all_orders()
    {
        $freelancer_id = Auth::guard('web')->user()->id;


        if(get_static_option('project_enable_disable') != 'disable'){
            $orders = Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->latest()->paginate(10);
            $queue_orders =  Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->where('status',0)->count();
            $active_orders =  Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->where('status',1)->count();
            $complete_orders = Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->where('status',3)->count();
            $cancel_orders = Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->where('status',4)->count();
        }else{
            $orders = Order::where('freelancer_id',$freelancer_id)->where('is_project_job', '!=', 'project')->whereHas('user')->where('payment_status','complete')->latest()->paginate(10);
            $queue_orders =  Order::where('freelancer_id',$freelancer_id)->where('is_project_job', '!=', 'project')->whereHas('user')->where('payment_status','complete')->where('status',0)->count();
            $active_orders =  Order::where('freelancer_id',$freelancer_id)->where('is_project_job', '!=', 'project')->whereHas('user')->where('payment_status','complete')->where('status',1)->count();
            $complete_orders = Order::where('freelancer_id',$freelancer_id)->where('is_project_job', '!=', 'project')->whereHas('user')->where('payment_status','complete')->where('status',3)->count();
            $cancel_orders = Order::where('freelancer_id',$freelancer_id)->where('is_project_job', '!=', 'project')->whereHas('user')->where('payment_status','complete')->where('status',4)->count();
        }


        if(moduleExists('HourlyJob')){
            $jobs = JobPost::with('job_creator','job_skills')
                ->whereHas('job_creator')
                ->where('on_off','1')
                ->where('status','1')
                ->where('job_approve_request','1')
                ->latest()
                ->take(5)->get();
        }else{
            $jobs = JobPost::with('job_creator','job_skills')
                ->whereHas('job_creator')
                ->where('on_off','1')
                ->where('status','1')
                ->where('job_approve_request','1')
                ->where('type','fixed')
                ->latest()
                ->take(5)->get();
        }


        return view('frontend.user.freelancer.order.orders',compact(['orders','queue_orders','active_orders','complete_orders','cancel_orders', 'jobs']));
    }

    // sort
    public function sort_by(Request $request)
    {
        $freelancer_id = Auth::guard('web')->user()->id;
        $query = Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->latest();

        if($request->order_type == 'all')
        {
            $orders = $query->paginate(10);
        }
        if($request->order_type == 'active')
        {
            $orders = $query->where('status',1)->paginate(10);
        }
        if($request->order_type == 'queue')
        {
            $orders = $query->where('status',0)->paginate(10);
        }
        if($request->order_type == 'cancel')
        {
            $orders = $query->where('status',4)->paginate(10);
        }
        if($request->order_type == 'complete')
        {
            $orders = $query->where('status',3)->paginate(10);
        }
        return view('frontend.user.freelancer.order.search-result', compact('orders'))->render();
    }

    // pagination
    public function pagination(Request $request)
    {
        if($request->ajax()){
            $freelancer_id = Auth::guard('web')->user()->id;
            $query = Order::where('freelancer_id',$freelancer_id)->whereHas('user')->where('payment_status','complete')->latest();
            if($request->order_type == 'all')
            {
                $orders = $query->latest()->paginate(10);
            }
            if($request->order_type == 'active')
            {
                $orders = $query->where('status',1)->paginate(10);
            }
            if($request->order_type == 'queue')
            {
                $orders = $query->where('status',0)->paginate(10);
            }
            if($request->order_type == 'cancel')
            {
                $orders = $query->where('status',4)->paginate(10);
            }
            if($request->order_type == 'complete')
            {
                $orders = $query->where('status',3)->paginate(10);
            }
            return view('frontend.user.freelancer.order.search-result', compact('orders'))->render();
        }
    }

    // order details
    public function order_details($id)
    {
        $freelancer_id = Auth::guard('web')->user()->id;
        $order_details = Order::with(['user:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,created_at,user_verified_status','order_submit_history'])
            ->whereHas('user')
            ->where('id',$id)->where('payment_status','complete')->where('freelancer_id',$freelancer_id)->first();
        return !empty($order_details) ? view('frontend.user.freelancer.order.order-details',compact('order_details')) : back();
    }

    // report order
    public function report(Request $request)
    {
        $request->validate([
            'report_title' => 'required|max:190',
            'report_description' => 'required',
        ]);

        Report::create([
            'order_id' => $request->report_order_id,
            'freelancer_id' => auth()->user()->id,
            'client_id' => $request->report_to_client_id,
            'reporter' => 'freelancer',
            'title' => $request->report_title,
            'description' => $request->report_description,
            'status' => 0
        ]);
        return back()->with(toastr_success(__('Report Successfully Send')));
    }

    // order accept
    public function order_accept($id)
    {
        //if order from job proposal then first find job_id from order and update the job current_status
        $find_order = Order::findOrFail($id);
        if($find_order && $find_order->is_project_job == 'job'){
            JobPost::where('id',$find_order->identity)->update(['current_status'=>1]);
        }

         Order::where('id',$id)->update(['status'=>1]);
         $order_milestone = OrderMilestone::where('order_id',$id)->first();
         if($order_milestone){
             OrderMilestone::where('id',$order_milestone->id)->update(['status'=>1]);
         }
         return back()->with(toastr_success(__('Order Successfully Accepted.')));
    }

    // cancel & decline
    public function order_decline(Request $request, $id)
    {
        $order_details = Order::select(['id','freelancer_id','user_id','price','payment_status','is_fixed_hourly'])->where('id',$id)->first();
        $cancel_or_decline = $request->cancel_or_decline_order;
        $cancel_or_decline == 'decline' ? Order::where('id',$id)->update(['status'=>5]) : Order::where('id',$id)->update(['status'=>4]);
        $msg = $cancel_or_decline == 'decline' ? __('Order decline by freelancer') : __('Order cancel by freelancer');
        $this->createDeclineWalletHistory($order_details->id,$order_details->freelancer_id,$order_details->user_id,$order_details->price,$order_details->payment_status,$cancel_or_decline,$msg);

        //update wallet balance only for fixed job
        if($order_details->is_fixed_hourly != 'hourly'){
            if($order_details->payment_status === 'complete'){
                $user = Wallet::select('balance')->where('user_id',$order_details->user_id)->first();
                Wallet::where('user_id',$order_details->user_id)->update([
                    'balance'=> $user->balance + $order_details->price
                ]);
            }
        }

        return $request->cancel_or_decline_order == 'decline' ? back()->with(toastr_success(__('Order Successfully Decline.'))) : back()->with(toastr_success(__('Order Successfully Cancel.')));
    }

    // order submit
    public function order_submit(Request $request)
    {
        if($request->hasFile('attachment')) {
            $allowedSize = get_static_option('max_upload_size') ?? '1048576';
            $allowedExtensions = json_decode(get_static_option('file_extensions'), true);
            if($allowedExtensions){
                $allowed_extensions = implode(',', $allowedExtensions);
                $request->validate([
                    'attachment' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                    'description'=>'required|max:300'
                ]);
            }else{
                $request->validate([
                    'attachment'=>'required|mimes:png,jpg,jpeg,pdf,docx,zip' . '|max:' . $allowedSize,
                    'description'=>'required|max:300'
                ]);
            }

            $attachment = $request->attachment;
            $attachment_ext = $attachment->extension();
            $attachment_name = 'order_attachment_' . time() . '.' . $attachment_ext;
            $attachment_path = 'assets/uploads/attachment/order';
            $attachment->move($attachment_path, $attachment_name);

            OrderSubmitHistory::create([
                'order_id'=>$request->order_id,
                'order_milestone_id'=>$request->order_milestone_id,
                'description'=>$request->description,
                'attachment'=>$attachment_name,
            ]);

            $type = 'Order';
            $admin_msg = __('Order submitted by freelancer');
            $client_msg = __('Your order has been submitted. Please check it.');
            $freelancer_id = Auth::guard('web')->user()->id;

            if($request->order_milestone_id){
                //update milestone status
                OrderMilestone::where('id',$request->order_milestone_id)->update(['status'=>4]);
            }else{
                //update order status
                Order::where('id',$request->order_id)->update(['status'=>2]);
            }

            notificationToAdmin($request->order_id,$freelancer_id,$type,$admin_msg);
            client_notification($request->order_id, $request->client_id, $type, $client_msg);
        }
        return back()->with(toastr_success(__('Order Successfully Submitted')));
    }

    //add rating after approve full order
    public function order_rating(Request $request,$id)
    {
        $query = Order::with('user:id,first_name')->select('id','freelancer_id','user_id','status')->where('id',$id)->where('freelancer_id',auth()->user()->id);
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
                $rating = Rating::where('order_id',$find_login_user_order->id)->where('sender_id',$find_login_user_order->freelancer_id)->first();
                if($rating){
                    return back()->with(toastr_warning( __('You have already submitted a review for this order. For a order you can submit only one review.')));
                }
                else{
                    $rating = Rating::create([
                        'order_id'=>$id,
                        'sender_id'=>auth()->user()->id,
                        'sender_type'=>2,
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
            return view('frontend.user.freelancer.order.rating.rating',compact(['id','find_login_user_order']));
        }
        return back();
    }

    // order decline wallet history
    private function createDeclineWalletHistory($order_id,$freelancer_id,$client_id,$order_price,$payment_status,$cancel_or_decline,$msg)
    {
        OrderDeclineHistory::create([
            'order_id'=>$order_id,
            'freelancer_id'=>$freelancer_id,
            'client_id'=>$client_id,
            'order_price'=>$order_price,
            'payment_status'=>$payment_status,
            'cancel_or_decline'=>$cancel_or_decline,
            'cancel_by'=>'freelancer',
        ]);

        OrderDeclineWalletHistory::create([
            'order_id'=>$order_id,
            'freelancer_id'=>$freelancer_id,
            'client_id'=>$client_id,
            'order_price'=>$order_price,
            'payment_status'=>$payment_status,
            'cancel_or_decline'=>$cancel_or_decline,
            'cancel_by'=>'freelancer',
        ]);

        notificationToAdmin($order_id,$freelancer_id,ucfirst($cancel_or_decline),$msg);
        client_notification($order_id, $client_id,'Order',__('Order cancel'));
    }

    function addTimeToTimestamp($start_time, $work_hour) {
        $date = new DateTime($start_time);
        list($hours, $minutes, $seconds) = explode(':', $work_hour);
        $interval = new DateInterval("PT{$hours}H{$minutes}M{$seconds}S");
        $date->add($interval);
        return $date->format('Y-m-d H:i:s');
    }

}
