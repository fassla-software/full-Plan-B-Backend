<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\Frontend\OrderService;
use App\Http\Services\Frontend\OrderServiceApi;
use App\Mail\OrderMail;
use App\Models\IndividualCommissionSetting;
use App\Models\JobPost;
use App\Models\JobProposal;
use App\Models\Order;
use App\Models\OrderMilestone;
use App\Models\OrderRequestRevision;
use App\Models\OrderSubmitHistory;
use App\Models\Project;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Chat\Entities\Offer;
use Modules\Wallet\Entities\Wallet;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    //confirm order
    /**
     * @throws ValidationException
     */
    public function user_order_confirm(Request $request)
    {
        $all_gateway = ['wallet','paypal','manual_payment','mollie','paytm','stripe','razorpay','flutterwave','paystack','marcadopago','instamojo','cashfree','payfast','midtrans','squareup','cinetpay','paytabs','billplz','zitopay','sitesway','toyyibpay','authorize_dot_net'];
        if(empty($request->selected_payment_gateway)){
            return response()->json(['msg'=> __('Please select a payment gateway before place an order')])->setStatusCode('422');
        }
        if (!in_array($request->selected_payment_gateway, $all_gateway)) {
            return response()->json(['msg'=> __('Please select a payment gateway before place an order')]);
        }

        $project = Project::where('id',$request->project_id)->first();
        $job = JobPost::where('id',$request->job_id_for_order)->first();
        $offer = Offer::with('milestones')->where('id',$request->offer_id_for_order)->first();
        $order_type = null;

        if($project) {
            if ($request->basic_standard_premium_type === $project->basic_title) {
                $type = $project->basic_title;
                $revision = $project->basic_revision;
                $delivery = $project->basic_delivery;
                $price = $project->basic_discount_charge ?? $project->basic_regular_charge;
            }
            if ($request->basic_standard_premium_type === $project->standard_title) {
                $type = $project->standard_title;
                $revision = $project->standard_revision;
                $delivery = $project->standard_delivery;
                $price = $project->standard_discount_charge ?? $project->standard_regular_charge;
            }
            if ($request->basic_standard_premium_type === $project->premium_title) {
                $type = $project->premium_title;
                $revision = $project->premium_revision;
                $delivery = $project->premium_delivery;
                $price = $project->premium_discount_charge ?? $project->premium_regular_charge;
            }

            $project_or_job = 'project';
            $freelancer_id = $project->user_id;

        }else{
            if($job){
                $proposal = JobProposal::select(['id','freelancer_id','amount','duration','revision'])->where('id',$request->proposal_id_for_order)->first();
                $price = $proposal->amount;
                $type = 'job';
                $revision = $proposal->revision;
                $delivery = $proposal->duration;
                $project_or_job = 'job';
                $freelancer_id = $proposal->freelancer_id;
                // Store an item in the cache for 10 minutes
                Cache::put('proposal_id_for_order', $proposal->id, 600);
            }
            if($offer){
                $price = $offer->price;
                $type = 'offer';
                $revision = $offer->revision;
                $delivery = $offer->deadline;
                $project_or_job = 'offer';
                $freelancer_id = $offer->freelancer_id;
            }
        }

        $client_id = auth('sanctum')->user()->id;

        $commission_type = get_static_option('admin_commission_type') ?? 'percentage';
        $commission_charge = get_static_option('admin_commission_charge') ?? 25;
        $transaction_type = get_static_option('transaction_fee_type');
        $transaction_charge = get_static_option('transaction_fee_charge') ?? 0;

        //commission and transaction amount calculate
        $user = User::select(['id','first_name','last_name','email'])->where('id',$freelancer_id)->first();
        $individual_commission = IndividualCommissionSetting::select(['user_id','admin_commission_type','admin_commission_charge'])->where('user_id',$user->id)->first();
        $commission_amount = commission_amount($price,$individual_commission,$commission_type,$commission_charge);
        $transaction_amount = transaction_amount($price,$transaction_type,$transaction_charge);

        //user payable amount calculate
        $payable_amount = $price - $commission_amount;
        $payment_status = $request->selected_payment_gateway === 'wallet' ? 'complete' : 'pending';

        $pay_by_milestone = $request->pay_by_milestone;
        $data=[];
        if(!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone'){

            $requestData= [];
            foreach(json_decode($request->milestones,true) as $milestone){
                $requestData["milestone_title"][] = $milestone['milestone_title'];
                $requestData["milestone_description"][] = $milestone['milestone_description'];
                $requestData["milestone_price"][] = $milestone['milestone_price'];
                $requestData["milestone_revision"][] = $milestone['milestone_revision'];
                $requestData["milestone_deadline"][] = $milestone['milestone_deadline'];
            }

            $data = (object)Validator::make($requestData, [
                'milestone_title.*' => 'required|max:100',
                'milestone_description.*' => 'required|max:1000',
                'milestone_price.*' => 'required',
                'milestone_revision.*' => 'required',
                'milestone_deadline.*' => 'required',
            ])->validated();

            $milestone_price = 0;
            foreach($data->milestone_price as $key => $attr) {$milestone_price += $data->milestone_price[$key];}
            if($milestone_price > $price || $milestone_price < $price){
                return response()->json(['msg'=> __('Milestone price must be equal to original price')])->setStatusCode('422');
            }
        }


        if($request->selected_payment_gateway == 'manual_payment')
        {
            $request->validate(['manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf']);
            return (new OrderServiceApi())->manual_order($data,$request, $client_id, $user->id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status);
        }

        elseif($request->selected_payment_gateway == 'wallet')
        {
            $wallet_amount = auth('sanctum')->user()->user_wallet?->balance ?? 0;

            //this code only related to hourly order
            if(moduleExists('HourlyJob') && !empty($job)){
                if($job->type == 'hourly') {
                    $request->validate([
                        'hourly_rate' => 'required|integer|min:1',
                        'estimated_hours' => 'required|integer|min:1',
                    ]);
                    JobPost::where('id',$job->id)->update([
                        'hourly_rate'=>$request->hourly_rate,
                        'estimated_hours'=>$request->estimated_hours,
                    ]);
                    $hourly_job = JobPost::where('id',$request->job_id_for_order)->first();
                    $price = $hourly_job->hourly_rate * $hourly_job->estimated_hours;
                    $commission_amount = commission_amount($price,$individual_commission,$commission_type,$commission_charge);
                    $payment_status = 'complete';
                    //user payable amount calculate
                    $payable_amount = $price - $commission_amount;
                    if($price > $wallet_amount){
                        return response()->json(['msg'=> __('Wallet balance must be equal or greater than original price')])->setStatusCode('422');
                    }
                    return (new OrderServiceApi())->hourly_order($request, $client_id, $user->id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status,$wallet_amount,$order_type);
                }
            }
            //hourly order end

            if($price > $wallet_amount){
                return response()->json(['msg'=> __('Wallet balance must be equal or greater than original price')])->setStatusCode('422');
            }
            return (new OrderServiceApi())->wallet_order($data,$request, $client_id, $user->id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status,$wallet_amount);
        }

        else
        {
            return (new OrderServiceApi())->digital_payment_gateway_order($data,$request, $client_id, $user->id, $project_or_job, $type, $revision, $delivery, ($price+$transaction_amount), $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $transaction_amount, $payable_amount, $payment_status);
        }
    }

    //payment update
    public function payment_update(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status' => 'required'
        ]);

        $order = Order::find($request->order_id);
        $last_order_id = $order->id;
        $user_id = $order->user_id;
        $freelancer_id = $order->freelancer_id;
        $project_or_job = $order->is_project_job;
        if (Cache::has('proposal_id_for_order')) {
            $proposal_id = Cache::get('proposal_id_for_order');
        }

        if (!empty($order) && $order->payment_status == 'pending' && $request->status == 1) {
            $client = User::select(['id', 'first_name', 'last_name', 'email'])->where('id', $user_id)->first();
            $freelancer = User::select(['id', 'first_name', 'last_name', 'email'])->where('id', $freelancer_id)->first();

            $data_to_hash = $client->email;
            $ctx = hash_init('sha256', HASH_HMAC, 'apipkey');
            hash_update($ctx, $data_to_hash);
            $secret_key = hash_final($ctx);

            if($request->secret_key == $secret_key){
                Order::where('id', $last_order_id)->where('user_id', $user_id)
                    ->update([
                        'price' => $order->price - $order->transaction_amount,
                        'payment_status' => 'complete',
                        'status' => 0,
                    ]);
            }else{
                return response()->json([
                    'msg' => __('Key does not match')
                ])->setStatusCode(422);
            }

            //update job proposal (hired 0 to 1) if the order created from job
                if ($project_or_job == 'job') {
                    JobProposal::where('id', $proposal_id)->update(['is_hired' => 1]);
                    Cache::forget('proposal_id_for_order');
                }

            notificationToAdmin($last_order_id, $user_id,'Order',__('New order placed'));
            freelancer_notification($last_order_id, $freelancer_id,'Order',__('You have a new order'));

            //email to admin
            try {
                Mail::to(get_static_option('site_global_email'))->send(new OrderMail($last_order_id,'admin'));
            } catch (\Exception $e) {}

            //email to client
            try {
                Mail::to($client->email)->send(new OrderMail($last_order_id,'client'));
            } catch (\Exception $e) {}

            //email to freelancer
            try {
                Mail::to($freelancer->email)->send(new OrderMail($last_order_id,'freelancer'));
            } catch (\Exception $e) {}
        }else{
            return response()->json([
                'msg' => __('Order not found')
            ]);
        }

        return response()->json([
            'status' => __('success'),
            'msg' => __('Order Status Updated Successfully')
        ]);
    }

    public function all_order()
    {
        $client_id = auth('sanctum')->user()->id;

        if(get_static_option('project_enable_disable') != 'disable'){

            $orders = Order::with([
                'freelancer:id,first_name,last_name,image,user_verified_status,check_online_status,country_id,state_id,load_from',
                'project:id,title',
                'job:id,title,hourly_rate'
            ])
                ->where('user_id', $client_id)
                ->where(function ($query) {
                    $query->whereHas('freelancer', function ($q) {
                        $q->where('payment_status', 'complete');
                    })
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('payment_gateway', 'manual_payment')
                                ->whereIn('payment_status', ['pending', 'complete']);
                        });
                })
                ->latest()
                ->paginate(10);


            $queue_orders = Order::with('freelancer:id,first_name,last_name,image,user_verified_status,check_online_status,country_id,state_id,load_from')
                ->where('user_id', $client_id)
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

        $top_projects = Project::select(['id', 'title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image','load_from'])
            ->whereHas('project_creator')
            ->where('project_on_off','1')
            ->where('status','1')
            ->latest()
            ->take(3)
            ->get();

        if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            $top_projects->transform(function ($project) {
                $project->project_cloud_image = render_frontend_cloud_image_if_module_exists('project/'.$project->image, load_from: $project->load_from);
                return $project;
            });
        }

        if($orders){
            $orderList = $orders->getCollection()->transform(function ($order){
                if($order->is_project_job == 'project') {
                    $order->job = null;
                }
                if($order->is_project_job == 'job') {
                    $order->project = null;
                }
                if($order->is_project_job == 'offer') {
                    unset($order->project,$order->job );
                    $order->project = null;
                    $order->job = null;
                }

                return $order;
            });

            $orders = $orders->setCollection($orderList);

            if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $orders->transform(function ($order) {
                    $order->freelancer->cloud_image = render_frontend_cloud_image_if_module_exists('profile/'.$order->freelancer?->image, load_from: $order->freelancer?->load_from);
                    return $order;
                });
            }

            return response()->json([
                'orders' => $orders->withQueryString(),
                'queue_orders' => $queue_orders,
                'active_orders' => $active_orders,
                'complete_orders' => $complete_orders,
                'cancel_orders' => $cancel_orders,
                'top_projects' => $top_projects,
                'image_path' => asset('assets/uploads/project/'),
                'freelancer_image_path' => asset('assets/uploads/profile/'),
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no order found.')]);
    }

    public function order_details($id)
    {
        $find_order = Order::find($id);
        if(empty($find_order)){
            return response()->json(['msg' => __('no order found.')])->setStatusCode(422);
        }
        $client_id = auth('sanctum')->user()->id;
        $order_type = Order::select(['id','is_project_job'])->where('id',$id)->first();

        if($order_type->is_project_job == 'offer'){
            $order_details = Order::with([
                'freelancer:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,user_verified_status,load_from',
                'order_submit_history'
            ])
                ->where('id', $id)
                ->where('user_id', $client_id)->first();
        }else{
            $order_details = Order::with([
                'freelancer:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,user_verified_status,load_from',
                'project:id,title',
                'job:id,title,hourly_rate,estimated_hours',
                'order_submit_history',
                'hourly_work_history:id,order_id,start_date,end_date,hours_worked,notes',
            ])
                ->where('id', $id)
                ->where('user_id', $client_id)->first();
        }

        $complete_orders = Order::select(['id', 'identity', 'status'])->where('freelancer_id', $order_details->freelancer_id)->where('status', 3)->get();
        $complete_orders_count = $complete_orders->count();

        if($order_details?->freelancer?->image){
            $order_details->freelancer->freelancer_cloud_image = render_frontend_cloud_image_if_module_exists('profile/'.$order_details?->freelancer?->image, load_from: $order_details?->freelancer?->load_from);
        }else{
            $order_details->freelancer->freelancer_cloud_image = null;
        }

        $count = 0;
        $rating_count = 0;
        $total_rating = 0;
        foreach ($complete_orders as $order) {
            $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
            if ($rating) {
                $total_rating = $total_rating + $rating->rating;
                $count = $count + 1;
                $rating_count = $rating_count + 1;
            }
        }
        $avg_rating = $count > 0 ? $total_rating / $count : 0;

        $earnings = OrderMilestone::where('order_id', $order_details->id)
            ->where('status', 2)
            ->sum('price');
        $mile_stones = OrderMilestone::where('order_id', $order_details->id)->get();
        $payable_amount = OrderMilestone::where('order_id', $order_details->id)
            ->where('status', '!=', 3)
            ->sum('price');

        if ($mile_stones->count() > 0) {
            $freelancer_payable_amount = $payable_amount - $earnings;
        } else {
            $freelancer_payable_amount = $order_details->payable_amount;
        }

        if($order_details->status === 3){
            $earned_balance = $order_details->payable_amount;
        }else{
            $earned_balance = OrderMilestone::where('order_id', $order_details->id)->where('status', 2)->sum('price');
        }

        $order_details->order_submit_history->transform(function ($order_submit_history){
            if (! file_exists(base_path('../assets/uploads/attachment/order/' . $order_submit_history->attachment))) {
                $order_submit_history->attachment = "";
            }
            return $order_submit_history;
        });

        if($order_details){
            return response()->json([
                'order_details' => $order_details,
                'mile_stones' => $mile_stones,
                'profile_image_path' => asset('assets/uploads/profile/'.$order_details?->freelancer?->image),
                'order_submit_history_path' => asset('assets/uploads/attachment/order/'),
                'country' => $order_details?->user?->user_country?->country,
                'state' => $order_details?->user?->user_state?->state,
                'city' => $order_details?->user?->user_city?->city,
                'complete_orders_count' => $complete_orders_count,
                'avg_rating' => round($avg_rating,1),
                'rating_count' => $rating_count,
                'earned_balance' => $earned_balance,
                'freelancer_payable_amount' => $freelancer_payable_amount,
                'freelancer_image_path' => asset('assets/uploads/profile/'),
                'order_submit_attachment_path' => asset('assets/uploads/attachment/order/'),
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no order found.')]);
    }

    //create revision
    public function request_revision(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $msg = __("You can't send revision request because your revision limit has been finished");

        $find_client_order = Order::where('user_id',$user_id)->where('id',$request->order_id)->first();
        if(empty($find_client_order)){
            return response()->json(['msg'=>__('This order is not related to you')])->setStatusCode('422');
        }

        if($request->order_milestone_id){
            $revision_left = OrderMilestone::select('id','revision_left')->where('id',$request->order_milestone_id)->first();
            if($revision_left->revision_left > 0){
                $this->create_request_revision($request->order_id, $request->order_milestone_id, $request->order_submit_history_id, $request->revision_description);

                OrderMilestone::where('id',$request->order_milestone_id)->update(['status'=>1]);
                OrderMilestone::find($request->order_milestone_id)->decrement('revision_left');
                OrderSubmitHistory::where('order_milestone_id',$request->order_milestone_id)->update(['status'=>2]);
            }else{
                return response()->json(['msg' => $msg])->setStatusCode('422');
            }
        }else{
            $revision_left = Order::select('id','revision_left')->where('id',$request->order_id)->first();
            if($revision_left->revision_left > 0){
                $this->create_request_revision($request->order_id, $request->order_milestone_id, $request->order_submit_history_id, $request->revision_description);

                Order::find($request->order_id)->decrement('revision_left');
                Order::where('id',$request->order_id)->update(['status'=>1]);
                OrderSubmitHistory::where('order_id',$request->order_id)->update(['status'=>2]);
            }else{
                return response()->json(['msg' => $msg])->setStatusCode('422');
            }
        }

        //order revision request
        $freelancer = Order::select('id','freelancer_id')->where('id',$request->order_id)->first();
        freelancer_notification($request->order_id, $freelancer->freelancer_id, 'Order',__('Request for revision'));
        return response()->json(['msg' => __('Revision request successfully created')]);
    }

    //approve order and milestone
    public function order_milestone_approve(Request $request)
    {
        $type =  empty($request->order_milestone_id) ? 'order' : 'milestone';

        $user_id = auth('sanctum')->user()->id;
        $find_client_order = Order::where('user_id',$user_id)->where('id',$request->order_id)->first();
        if(empty($find_client_order)){
            return response()->json(['msg'=>__('This order is not related to you')])->setStatusCode('422');
        }

        if($type == 'milestone'){
            $id = $request->order_milestone_id;
            $milestone = OrderMilestone::where('id',$id)->first();

            if(empty($milestone)){
                return response()->json(['msg' => __('Milestone not found')])->setStatusCode('422');
            }
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
                return response()->json(['msg' => __('Milestone Successfully Completed')]);
            }else{
                //update order status complete after complete all milestones
                Order::where('id',$milestone->order_id)->update(['status'=>3]);
                return response()->json(['msg' => __('Order Successfully Completed')]);
            }

        }else{
            $id = $request->order_id;
            $order = Order::where('id',$id)->first();
            $total_earning = UserEarning::where('user_id',$order->freelancer_id)->first();

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

            //update freelancer wallet balance
            $freelancer_wallet = Wallet::where('user_id',$order->freelancer_id)->first();
            Wallet::where('user_id',$order->freelancer_id)->update([
                'balance'=>$freelancer_wallet->balance + $order->payable_amount,
                'remaining_balance'=> $freelancer_wallet->remaining_balance+$order->payable_amount
            ]);

            //update order status to complete
            Order::where('id',$id)->update(['status'=>3]);

            //approve submitted work
            $order_submit_history = OrderSubmitHistory::where('order_id',$id)->OrderBy('id','DESC')->first();
            OrderSubmitHistory::where('id',$order_submit_history->id)->update(['status'=>1]);

            //freelancer and admin notification
            freelancer_notification($milestone->order_id ?? $order->id, $order->freelancer_id, 'Order',__('Order accepted by client'));
            notificationToAdmin($milestone->order_id ?? $order->id, Auth::user()->id,'Order',__('Order accepted by client'));

            //if order from job proposal then first find job_id from order and update the job current_status
            $find_order = Order::findOrFail($id);
            if($order && $order->is_project_job == 'job'){
                JobPost::where('id',$find_order->identity)->update(['current_status'=>2]);
            }
            return response()->json(['msg' => __('Order Successfully Completed')]);
        }

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
}
