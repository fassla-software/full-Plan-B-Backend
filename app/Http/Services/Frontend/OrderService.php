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
use phpDocumentor\Reflection\Type;

class OrderService
{
    private const CANCEL_ROUTE = 'order.payment.cancel.static';

  //user login
  public function user_login($request)
  {
      Auth::logout();
      $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
      $request->validate([
          'username' => 'required|string',
          'password' => 'required|min:6'
      ],
          [
              'username.required' => sprintf(__('%s is required'),$email_or_username),
              'password.required' => __('password is required')
          ]);

      return Auth::guard('web')->attempt([$email_or_username => $request->username, 'password' => $request->password])
          ? response()->json(['status' => 'success'])
          : response()->json(['msg' => sprintf(__('Your %s or Password Is Wrong !!'),$email_or_username),'status' => 'failed']);
  }

    //manual order
  public function manual_order($request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status,$order_type)
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
                  'order_type' => $order_type
              ]);
              $last_order_id = $order->id;
              $type_ = 'Order';
              $msg = __('New order placed');
              notificationToAdmin($last_order_id, $client_id, $type_, $msg);
              freelancer_notification($last_order_id, $freelancer_id, $type_, $msg);

              //check and create project order with milestone
              if(!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone'){
                  self::createMilestone($last_order_id,$request);
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

              toastr_success('Order successfully completed.');
              $new_order_id = getLastOrderId($last_order_id);
              return redirect()->route('order.user.success.page',$new_order_id);
          }else{
              return back()->with(toastr_warning(__('Image type not supported')));
          }
      }
  }

  //wallet order
    public function wallet_order($request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $payable_amount, $payment_status, $wallet_balance,$order_type)
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
            'order_type' => $order_type
        ]);

        $last_order_id = $order->id;
        $type_ = 'Order';
        $msg = __('New order placed');
        notificationToAdmin($last_order_id, $client_id, $type_, $msg);
        freelancer_notification($last_order_id, $freelancer_id, $type_, $msg);


        //check and create milestone
        if (!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone') {
            self::createMilestone($last_order_id, $request);
        }

        //check and create custom offer order with milestone
        if($project_or_job == 'offer' && $offer?->milestones->count() >= 1){
            self::createOfferOrderMilestone($last_order_id,$request);
        }

        //status 1 means the offer is hired
        if($project_or_job == 'offer'){
            Offer::where('id',$request->offer_id_for_order)->update(['status'=>1]);
        }

        if (Auth::guard('web')->check()){
            $user_type = Auth::user()->user_type == 1 ? 'client' : 'freelancer';
            $client_email = Auth::user()->email;
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

        toastr_success('Order successfully completed.');
        $new_order_id = getLastOrderId($last_order_id);
        return redirect()->route('order.user.success.page',$new_order_id);
    }

    // order by payment gateway
    public function digital_payment_gateway_order($request, $client_id, $freelancer_id, $project_or_job, $type, $revision, $delivery, $price, $commission_type, $commission_charge, $commission_amount, $transaction_type, $transaction_charge, $transaction_amount, $payable_amount, $payment_status,$order_type)
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
            'order_type' => $order_type
        ]);

        $last_order_id = $order->id;

        //check and create milestone
        if (!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone') {
            self::createMilestone($last_order_id, $request);
        }

        //check and create custom offer order with milestone
        if($project_or_job == 'offer' && $offer?->milestones->count() >= 1){
            self::createOfferOrderMilestone($last_order_id,$request);
        }

        //status 1 means the offer is hired
        if($project_or_job == 'offer'){
            Offer::where('id',$request->offer_id_for_order)->update(['status'=>1]);
        }

        $auth_user = Auth::user();
        $title = __('Order');
        $total = $price;
        $name = $auth_user->first_name.' '.$auth_user->last_name;
        $email = $auth_user->email;
        session()->put('user_id',$auth_user->id);
        session()->put('freelancer_id',$freelancer_id);
        session()->put('project_or_job',$project_or_job);
        session()->put('proposal_id',$request->proposal_id_for_order);
        $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'),$last_order_id,$email,$name);

        if ($request->selected_payment_gateway === 'paypal') {
            try {
                return PaymentGatewayRequestHelper::paypal()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.paypal.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'paytm'){
            try {
                return PaymentGatewayRequestHelper::paytm()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.paytm.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif ($request->selected_payment_gateway === 'mollie'){
            try {
                return PaymentGatewayRequestHelper::mollie()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.mollie.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'stripe'){
            try {
                return PaymentGatewayRequestHelper::stripe()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.stripe.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'razorpay'){
            try {
                return PaymentGatewayRequestHelper::razorpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.razorpay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'flutterwave'){
            try {
                return PaymentGatewayRequestHelper::flutterwave()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.flutterwave.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'paystack'){
            try {
                return PaymentGatewayRequestHelper::paystack()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('paystack.ipn.all'),'order'));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'sslcommerce'){
            try {
                return PaymentGatewayRequestHelper::sslcommerz()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('sslcommerce.ipn.all'),'order'));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'payfast'){
            try {
                return PaymentGatewayRequestHelper::payfast()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.payfast.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'cashfree'){
            try {
                return PaymentGatewayRequestHelper::cashfree()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.cashfree.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'instamojo'){
            try {
                return PaymentGatewayRequestHelper::instamojo()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.instamojo.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'marcadopago'){
            try {
                return PaymentGatewayRequestHelper::marcadopago()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.marcadopago.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }

        }
        elseif($request->selected_payment_gateway === 'midtrans'){
            try {
                return PaymentGatewayRequestHelper::midtrans()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.midtrans.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'squareup'){
            try {
                return PaymentGatewayRequestHelper::squareup()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.squareup.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'cinetpay'){
            try {
                return PaymentGatewayRequestHelper::cinetpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.cinetpay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'paytabs'){
            try {
                return PaymentGatewayRequestHelper::paytabs()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.paytabs.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }

        elseif($request->selected_payment_gateway === 'billplz'){
            try {
                return PaymentGatewayRequestHelper::billplz()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.billplz.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'zitopay'){
            try {
                return PaymentGatewayRequestHelper::zitopay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.zitopay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'toyyibpay'){
            try {
                return PaymentGatewayRequestHelper::toyyibpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.toyyibpay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'authorize_dot_net'){
            try {
                return PaymentGatewayRequestHelper::authorizenet()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.authorize.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'pagali'){
            try {
                return PaymentGatewayRequestHelper::pagalipay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.pagali.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'sitesway'){
            try {
                return PaymentGatewayRequestHelper::sitesway()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.siteways.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'iyzipay'){
            try {
                return PaymentGatewayRequestHelper::iyzipay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.iyzipay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }
        elseif($request->selected_payment_gateway === 'kineticpay'){
            try {
                return PaymentGatewayRequestHelper::kineticpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.kineticpay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }

        elseif($request->selected_payment_gateway === 'awdpay'){
            try {
                return PaymentGatewayRequestHelper::awdpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('pro.awdpay.ipn.order')));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }

        elseif($request->selected_payment_gateway === 'yoomoney'){
            try {
                return PaymentGatewayRequestHelper::yoomoney()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('yoomoney.ipn.all'),'order'));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }

        elseif($request->selected_payment_gateway === 'coinpayments'){
            try {
                return PaymentGatewayRequestHelper::coinpayments()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,route('coinpayment.ipn.all'),'order'));
            }catch (\Exception $e){
                toastr_error($e->getMessage());
                return back();
            }
        }

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

        toastr_success('Order successfully completed.');
        $new_order_id = getLastOrderId($last_order_id);
        return redirect()->route('order.user.success.page',$new_order_id);
    }

    //create project order milestone
    private function createMilestone($last_order_id,$request)
    {
        $commission_type = get_static_option('admin_commission_type') ?? 'percentage';
        $commission_charge = get_static_option('admin_commission_charge') ?? 25;
        $project = Project::where('id',$request->project_id)->first();
        $user = User::select('id','first_name','last_name','email')->where('id',$project->user_id)->first();
        $individual_commission = IndividualCommissionSetting::select(['user_id','admin_commission_type','admin_commission_charge'])->where('user_id',$user->id)->first();

        $arr = [];
        foreach($request->milestone_title as $key => $attr) {
            $commission_amount = commission_amount($request->milestone_price[$key],$individual_commission,$commission_type,$commission_charge);
            $arr[] = [
                'order_id' => $last_order_id,
                'title' => $request->milestone_title[$key],
                'description' => $request->milestone_description[$key],
                'price' => $request->milestone_price[$key] - $commission_amount,
                'revision' => $request->milestone_revision[$key],
                'revision_left' => $request->milestone_revision[$key],
                'deadline' => $request->milestone_deadline[$key],
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

    private function buildPaymentArg($total,$title,$description,$last_order_id,$email,$name,$ipn_route,$source=null)
    {
        return [
            'amount' => $total,
            'title' => $title,
            'description' => $description,
            'ipn_url' => $ipn_route,
            'order_id' => $last_order_id,
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE),
            'success_url' => route('order.user.success.page',$last_order_id),
            'email' => $email,
            'name' => $name,
            'payment_type' => $source,
        ];
    }
}
