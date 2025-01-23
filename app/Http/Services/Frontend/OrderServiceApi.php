<?php

namespace App\Http\Services\Frontend;

use App\Helper\PaymentGatewayRequestHelper;
use App\Mail\OrderMail;
use App\Models\IndividualCommissionSetting;
use App\Models\JobProposal;
use App\Models\Order;
use App\Models\OrderMilestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Modules\Chat\Entities\Offer;
use Modules\Wallet\Entities\Wallet;

class OrderServiceApi
{
    private const CANCEL_ROUTE = 'order.payment.cancel.static';

  //manual order
  public function manual_order($data,$request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status)
  {
      $pay_by_milestone = $request->pay_by_milestone;
      $offer = Offer::with('milestones')->where('id',$request->offer_id_for_order)->first();

      if($request->project_id){ $identity = $request->project_id; }
      if($request->job_id_for_order){ $identity = $request->job_id_for_order; }
      if($request->offer_id_for_order){ $identity = $request->offer_id_for_order; }

      if($request->hasFile('manual_payment_image')){
          $manual_payment_image = $request->manual_payment_image;
          $img_ext = $manual_payment_image->extension();
          $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;

          if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
              $manual_image_path = 'assets/uploads/manual-payment/order';

              if (in_array($img_ext,['jpg','jpeg','png'])) {
                  $resize_full_image = Image::make($request->manual_payment_image);
                  $resize_full_image->save($manual_image_path .'/'. $manual_payment_image_name);
              }else{
                  $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
              }

              $order = Order::create([
                  'user_id' => $client_id,
                  'freelancer_id' => $freelancer_id,
                  'identity' => $identity,
                  'is_project_job' => $project_or_job,
                  'is_basic_standard_premium_custom' => $type,
                  'revision' => $revision,
                  'revision_left' => $revision,
                  'delivery_time' => $delivery,
                  'description' => $request->order_description ?? NULL,
                  'price' => $price,
                  'commission_type' => $commission_type,
                  'commission_charge' => $commission_charge,
                  'commission_amount' => $commission_amount,
                  'transaction_type' => $transaction_type,
                  'transaction_charge' => $transaction_charge,
                  'transaction_amount' => 0,
                  'payable_amount' => $payable_amount,
                  'payment_gateway' => $request->selected_payment_gateway,
                  'payment_status' => $payment_status,
                  'manual_payment_image' => $manual_payment_image_name,
                  'status' => 0,
              ]);
              $last_order_id = $order->id;
              $type_ = 'Order';
              $msg = __('New order placed');
              notificationToAdmin($last_order_id, $client_id, $type_, $msg);
              freelancer_notification($last_order_id, $freelancer_id, $type_, $msg);

              //check and create project order with milestone
              if(!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone'){
                  self::createMilestone($last_order_id,$request,$data);
              }

              //check and create custom offer order with milestone
              if($project_or_job == 'offer' && $offer?->milestones->count() >= 1){
                  self::createOfferOrderMilestone($last_order_id,$request);
              }

              $client = User::select(['id','email'])->where('id',$client_id)->first();
              $freelancer = User::select(['id','email'])->where('id',$freelancer_id)->first();

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

              //update job proposal (hired 0 to 1) if the order created from job
              if($project_or_job == 'job'){
                  JobProposal::where('id',$request->proposal_id_for_order)->update(['is_hired'=>1]);
              }

              //status 1 means the offer is hired
              if($project_or_job == 'offer'){
                  Offer::where('id',$request->offer_id_for_order)->update(['status'=>1]);
              }

              $order_details = Order::with(['user','freelancer'])->where('id',$last_order_id)->first();
              return response()->json([
                  'msg'=> __('Order successfully completed.'),
                  'order_details'=> $order_details,
              ]);
          }else{
              return response()->json(['msg'=> __('Image type not supported')]);
          }
      }
  }

  //wallet order
    public function wallet_order($data,$request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status, $wallet_balance)
    {
        $pay_by_milestone = $request->pay_by_milestone;
        $offer = Offer::with('milestones')->where('id',$request->offer_id_for_order)->first();

        if($request->project_id){ $identity = $request->project_id; }
        if($request->job_id_for_order){ $identity = $request->job_id_for_order; }
        if($request->offer_id_for_order){ $identity = $request->offer_id_for_order; }

        $order = Order::create([
            'user_id' => $client_id,
            'freelancer_id' => $freelancer_id,
            'identity' => $identity,
            'is_project_job' => $project_or_job,
            'is_basic_standard_premium_custom' => $type,
            'revision' => $revision,
            'revision_left' => $revision,
            'delivery_time' => $delivery,
            'description' => $request->order_description ?? NULL,
            'price' => $price,
            'commission_type' => $commission_type,
            'commission_charge' => $commission_charge,
            'commission_amount' => $commission_amount,
            'transaction_type' => $transaction_type,
            'transaction_charge' => $transaction_charge,
            'transaction_amount' => 0,
            'payable_amount' => $payable_amount,
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => $payment_status,
            'status' => 0,
        ]);

        $last_order_id = $order->id;
        $type_ = 'Order';
        $msg = __('New order placed');
        notificationToAdmin($last_order_id, $client_id, $type_, $msg);
        freelancer_notification($last_order_id, $freelancer_id, $type_, $msg);


        //check and create milestone
        if (!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone') {
            self::createMilestone($last_order_id, $request,$data);
        }

        //check and create custom offer order with milestone
        if($project_or_job == 'offer' && $offer?->milestones->count() >= 1){
            self::createOfferOrderMilestone($last_order_id,$request);
        }

        //status 1 means the offer is hired
        if($project_or_job == 'offer'){
            Offer::where('id',$request->offer_id_for_order)->update(['status'=>1]);
        }

        if (auth('sanctum')->check()){
            $user_type = auth('sanctum')->user()->user_type == 1 ? 'client' : 'freelancer';
            $client_email = auth('sanctum')->user()->email;
            $user_id = $user_type == 'client' ? $client_id : $freelancer_id;
            Wallet::where('user_id',$user_id)->update([
                'balance'=> ($wallet_balance - $price)
            ]);
        }

        $freelancer = User::select(['id','email'])->where('id',$freelancer_id)->first();

        //email to admin
        try {
            Mail::to(get_static_option('site_global_email'))->send(new OrderMail($last_order_id,'admin'));
        } catch (\Exception $e) {}

        //email to client
        try {
            Mail::to($client_email)->send(new OrderMail($last_order_id,'client'));
        } catch (\Exception $e) {}

        //email to freelancer
        try {
            Mail::to($freelancer->email)->send(new OrderMail($last_order_id,'freelancer'));
        } catch (\Exception $e) {}

        //update job proposal (hired 0 to one) if the order created from job
        if($project_or_job == 'job'){
            JobProposal::where('id',$request->proposal_id_for_order)->update(['is_hired'=>1]);
        }

        $order_details = Order::with(['user','freelancer'])->where('id',$last_order_id)->first();
        return response()->json([
            'msg'=> __('Order successfully completed.'),
            'order_details'=> $order_details,
        ]);
    }

    // order by payment gateway
    public function digital_payment_gateway_order($data,$request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $transaction_amount, $payable_amount, $payment_status)
    {
        $pay_by_milestone = $request->pay_by_milestone;
        $offer = Offer::with('milestones')->where('id',$request->offer_id_for_order)->first();

        if($request->project_id){ $identity = $request->project_id; }
        if($request->job_id_for_order){ $identity = $request->job_id_for_order; }
        if($request->offer_id_for_order){ $identity = $request->offer_id_for_order; }

        $order = Order::create([
            'user_id' => $client_id,
            'freelancer_id' => $freelancer_id,
            'identity' => $identity,
            'is_project_job' => $project_or_job,
            'is_basic_standard_premium_custom' => $type,
            'revision' => $revision,
            'revision_left' => $revision,
            'delivery_time' => $delivery,
            'description' => $request->order_description ?? NULL,
            'price' => $price,
            'commission_type' => $commission_type,
            'commission_charge' => $commission_charge,
            'commission_amount' => $commission_amount,
            'transaction_type' => $transaction_type,
            'transaction_charge' => $transaction_charge,
            'transaction_amount' => $transaction_amount,
            'payable_amount' => $payable_amount,
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => $payment_status,
        ]);

        $last_order_id = $order->id;

        //check and create milestone
        if (!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone'){
            self::createMilestone($last_order_id, $request,$data);
        }

        //check and create custom offer order with milestone
        if($project_or_job == 'offer' && $offer?->milestones->count() >= 1){
            self::createOfferOrderMilestone($last_order_id,$request);
        }

        //status 1 means the offer is hired
        if($project_or_job == 'offer'){
            Offer::where('id',$request->offer_id_for_order)->update(['status'=>1]);
        }

        $order_details = Order::with(['user','freelancer'])->where('id',$last_order_id)->first();
        return response()->json([
            'msg'=> __('Order successfully completed.'),
            'order_details'=> $order_details,
        ]);

    }

    //hourly order
    public function hourly_order($request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status, $wallet_balance,$order_type)
    {
        if($request->job_id_for_order){ $identity = $request->job_id_for_order; }

        $order = Order::create([
            'user_id' => $client_id,
            'freelancer_id' => $freelancer_id,
            'identity' => $identity,
            'is_project_job' => $project_or_job,
            'is_basic_standard_premium_custom' => $type,
            'revision' => $revision,
            'revision_left' => $revision,
            'delivery_time' => $delivery,
            'description' => $request->order_description ?? NULL,
            'price' => $price,
            'commission_type' => $commission_type,
            'commission_charge' => $commission_charge,
            'commission_amount' => $commission_amount,
            'transaction_type' => $transaction_type,
            'transaction_charge' => $transaction_charge,
            'transaction_amount' => 0,
            'payable_amount' => $payable_amount,
            'payment_gateway' => 'wallet',
            'payment_status' => $payment_status,
            'status' => 0,
            'is_fixed_hourly' => 'hourly',
            'order_type' => $order_type
        ]);

        $last_order_id = $order->id;
        $type_ = 'Order';
        $msg = __('New order placed');
        notificationToAdmin($last_order_id, $client_id, $type_, $msg);
        freelancer_notification($last_order_id, $freelancer_id, $type_, $msg);

        if (Auth::guard('web')->check()){
            $user_type = Auth::user()->user_type == 1 ? 'client' : 'freelancer';
            $client_email = Auth::user()->email;
            $user_id = $user_type == 'client' ? $client_id : $freelancer_id;
        }

        $freelancer = User::select(['id','email'])->where('id',$freelancer_id)->first();

        //email to admin
        try {
            Mail::to(get_static_option('site_global_email'))->send(new OrderMail($last_order_id,'admin'));
        } catch (\Exception $e) {}

        //email to client
        try {
            Mail::to($client_email)->send(new OrderMail($last_order_id,'client'));
        } catch (\Exception $e) {}

        //email to freelancer
        try {
            Mail::to($freelancer->email)->send(new OrderMail($last_order_id,'freelancer'));
        } catch (\Exception $e) {}

        //update job proposal (hired 0 to one) if the order created from job
        if($project_or_job == 'job'){
            JobProposal::where('id',$request->proposal_id_for_order)->update(['is_hired'=>1]);
        }

        $order_details = Order::with(['user','freelancer'])->where('id',$last_order_id)->first();
        return response()->json([
            'msg'=> __('Order successfully completed.'),
            'order_details'=> $order_details,
        ]);
    }

    //create project order milestone
    private function createMilestone($last_order_id,$request,$data)
    {
        $commission_type = get_static_option('admin_commission_type') ?? 'percentage';
        $commission_charge = get_static_option('admin_commission_charge') ?? 25;
        $project = Project::where('id',$request->project_id)->first();
        $user = User::select('id','first_name','last_name','email')->where('id',$project->user_id)->first();
        $individual_commission = IndividualCommissionSetting::select(['user_id','admin_commission_type','admin_commission_charge'])->where('user_id',$user->id)->first();


        $arr = [];
        foreach($data->milestone_title as $key => $attr) {
            $commission_amount = commission_amount($data->milestone_price[$key],$individual_commission,$commission_type,$commission_charge);
            $arr[] = [
                'order_id' => $last_order_id,
                'title' => $data->milestone_title[$key],
                'description' => $data->milestone_description[$key],
                'price' => $data->milestone_price[$key] - $commission_amount,
                'revision' => $data->milestone_revision[$key],
                'revision_left' => $data->milestone_revision[$key],
                'deadline' => $data->milestone_deadline[$key],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        OrderMilestone::insert($arr);
    }

    //create offer order milestone
    private function createOfferOrderMilestone($last_order_id,$request)
    {
        $commission_type = get_static_option('admin_commission_type') ?? 'percentage';
        $commission_charge = get_static_option('admin_commission_charge') ?? 25;
        $offer = Offer::with('milestones')->where('id',$request->offer_id_for_order)->first();
        $individual_commission = IndividualCommissionSetting::select(['user_id','admin_commission_type','admin_commission_charge'])->where('user_id',$offer->freelancer_id)->first();

        $arr = [];
        foreach($offer->milestones as $key => $attr) {
            $commission_amount = commission_amount($attr['price'] ,$individual_commission,$commission_type,$commission_charge);
            $arr[] = [
                'order_id' => $last_order_id,
                'title' => $attr['title'],
                'description' => $attr['description'],
                'price' => $attr['price'] - $commission_amount,
                'revision' => $attr['revision'],
                'revision_left' => $attr['revision'],
                'deadline' => $attr['deadline'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        OrderMilestone::insert($arr);
    }
}
