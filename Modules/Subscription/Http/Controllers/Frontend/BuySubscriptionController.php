<?php

namespace Modules\Subscription\Http\Controllers\Frontend;

use App\Helper\PaymentGatewayRequestHelper;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Entities\UserSubscription;
use Modules\Wallet\Entities\Wallet;

class BuySubscriptionController extends Controller
{
    private const CANCEL_ROUTE = 'subscriptions.buy.payment.cancel.static';
    public function subscription_payment_cancel_static()
    {
        return view('subscription::frontend.subscriptions.cancel');
    }

    //buy subscription
    public function buy_subscription(Request $request)
    {
        if(isset($request->subscription_id)){
            $user = Auth::user();
            $subscription_details = Subscription::with('subscription_type:id,validity')->select(['id','subscription_type_id','price','limit'])
                ->where('id',$request->subscription_id)
                ->where('status','1')->first();

            if($subscription_details){
                $expire_date = Carbon::now()->addDays($subscription_details?->subscription_type?->validity);
                $title = __('Buy Subscription');
                $total = $subscription_details->price;
                $limit = $subscription_details->limit;
                $name = $user->first_name.' '.$user->last_name;
                $email = $user->email;
                $user_type = $user->user_type == 1 ? 'client' : 'freelancer';
                $payment_status = $request->selected_payment_gateway === 'wallet' ? 'complete' : 'pending';
                $status = $request->selected_payment_gateway === 'wallet' ? 1 : 0;
                session()->put('user_id',$user->id);
                session()->put('user_type',$user_type);


                if($request->selected_payment_gateway === 'manual_payment')
                {
                    $request->validate(['manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf']);

                    if($request->hasFile('manual_payment_image')){
                        $manual_payment_image = $request->manual_payment_image;
                        $img_ext = $manual_payment_image->extension();

                        $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;
                        if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
                            $manual_image_path = 'assets/uploads/manual-payment/subscription';
                            if (in_array($img_ext,['jpg','jpeg','png'])) {
                                $resize_full_image = Image::make($request->manual_payment_image);
                                $resize_full_image->save($manual_image_path .'/'. $manual_payment_image_name);
                            }else{
                                $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
                            }

                            $buy_subscription = UserSubscription::create([
                                'user_id' => $user->id,
                                'subscription_id' => $subscription_details->id,
                                'price' => $total,
                                'limit' => $limit,
                                'expire_date' => $expire_date,
                                'payment_gateway' => $request->selected_payment_gateway,
                                'manual_payment_image' => $manual_payment_image_name,
                                'payment_status' => $payment_status,
                                'status' => $status,
                            ]);
                            $last_subscription_id = $buy_subscription->id;
                            $this->adminNotification($last_subscription_id,$user->id);
                        }else{
                            return back()->with(toastr_warning(__('Image type not supported')));
                        }
                    }

                    $this->sendEmail($name,$last_subscription_id,$email);
                    toastr_success('Subscription purchase success. Your subscription will be usable after admin approval');
                    return redirect()->route($user_type.'.'.'subscriptions.all');
                }
                elseif($request->selected_payment_gateway === 'wallet')
                {
                    $wallet_balance = Wallet::select('balance')->where('user_id',$user->id)->first();
                    if(isset($wallet_balance) && $wallet_balance->balance > $total){
                        $buy_subscription = UserSubscription::create([
                            'user_id' => $user->id,
                            'subscription_id' => $subscription_details->id,
                            'price' => $total,
                            'limit' => $limit,
                            'expire_date' => $expire_date,
                            'payment_gateway' => $request->selected_payment_gateway,
                            'payment_status' => $payment_status,
                            'status' => $status,
                        ]);
                        $last_subscription_id = $buy_subscription->id;
                        $this->adminNotification($last_subscription_id,$user->id);
                        Wallet::where('user_id',$user->id)->update(['balance'=> $wallet_balance->balance - $total]);

                    }else{
                        return back()->with(toastr_warning(__('Please deposit to your wallet and try again')));
                    }
                    $this->sendEmail($name,$last_subscription_id,$email);
                    toastr_success('Subscription purchase success. Your subscription will be usable after admin approval');
                    return redirect()->route($user_type.'.'.'subscriptions.all');
                }
                else
                {
                    $buy_subscription = UserSubscription::create([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription_details->id,
                        'price' => $total,
                        'limit' => $limit,
                        'expire_date' => $expire_date,
                        'payment_gateway' => $request->selected_payment_gateway,
                    ]);

                    $last_subscription_id = $buy_subscription->id;
                    $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'),$last_subscription_id,$email,$name);

                    if ($request->selected_payment_gateway === 'paypal') {
                        try {
                            return PaymentGatewayRequestHelper::paypal()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.paypal.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'paytm'){
                        try {
                            return PaymentGatewayRequestHelper::paytm()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.paytm.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif ($request->selected_payment_gateway === 'mollie'){
                        try {
                            return PaymentGatewayRequestHelper::mollie()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.mollie.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'stripe'){
                        try {
                            return PaymentGatewayRequestHelper::stripe()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.stripe.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'razorpay'){
                        try {
                            return PaymentGatewayRequestHelper::razorpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.razorpay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'flutterwave'){
                        try {
                            return PaymentGatewayRequestHelper::flutterwave()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.flutterwave.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'paystack'){
                        try {
                            return PaymentGatewayRequestHelper::paystack()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('paystack.ipn.all'),'subscription'));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'sslcommerce'){
                        try {
                            return PaymentGatewayRequestHelper::sslcommerz()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('sslcommerce.ipn.all'),'subscription'));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'payfast'){
                        try {
                            return PaymentGatewayRequestHelper::payfast()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.payfast.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'cashfree'){
                        try {
                            return PaymentGatewayRequestHelper::cashfree()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.cashfree.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'instamojo'){
                        try {
                            return PaymentGatewayRequestHelper::instamojo()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.instamojo.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'marcadopago'){
                        try {
                            return PaymentGatewayRequestHelper::marcadopago()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.marcadopago.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }

                    }
                    elseif($request->selected_payment_gateway === 'midtrans'){
                        try {
                            return PaymentGatewayRequestHelper::midtrans()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.midtrans.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'squareup'){
                        try {
                            return PaymentGatewayRequestHelper::squareup()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.squareup.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'cinetpay'){
                        try {
                            return PaymentGatewayRequestHelper::cinetpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.cinetpay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'paytabs'){

                        try {
                            return PaymentGatewayRequestHelper::paytabs()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.paytabs.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'billplz'){
                        try {
                            return PaymentGatewayRequestHelper::billplz()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.billplz.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'zitopay'){
                        try {
                            return PaymentGatewayRequestHelper::zitopay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.zitopay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'toyyibpay'){
                        try {
                            return PaymentGatewayRequestHelper::toyyibpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.toyyibpay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'authorize_dot_net'){
                        try {
                            return PaymentGatewayRequestHelper::authorizenet()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.authorize.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'pagali'){
                        try {
                            return PaymentGatewayRequestHelper::pagalipay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.pagali.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'sitesway'){
                        try {
                            return PaymentGatewayRequestHelper::sitesway()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.siteways.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'iyzipay'){
                        try {
                            return PaymentGatewayRequestHelper::iyzipay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.iyzipay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'kineticpay'){
                        try {
                            return PaymentGatewayRequestHelper::kineticpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.kineticpay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'awdpay'){
                        try {
                            return PaymentGatewayRequestHelper::awdpay()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('bs.awdpay.ipn.subscription')));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                    elseif($request->selected_payment_gateway === 'yoomoney'){
                        try {
                            return PaymentGatewayRequestHelper::yoomoney()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('yoomoney.ipn.all'),'subscription'));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }

                    elseif($request->selected_payment_gateway === 'coinpayments'){
                        try {
                            return PaymentGatewayRequestHelper::coinpayments()->charge_customer($this->buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,route('coinpayment.ipn.all'),'subscription'));
                        }catch (\Exception $e){
                            toastr_error($e->getMessage());
                            return back();
                        }
                    }
                }
            }
        }
    }

    private function buildPaymentArg($total,$title,$description,$last_subscription_id,$email,$name,$user_type,$ipn_route,$source=null)
    {
        return [
            'amount' => $total,
            'title' => $title,
            'description' => $description,
            'ipn_url' => $ipn_route,
            'order_id' => $last_subscription_id,
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE,$last_subscription_id),
            'success_url' => route($user_type.'.'.'subscriptions.all'),
            'email' => $email,
            'name' => $name,
            'payment_type' => $source,
        ];
    }

  //send email
    private function sendEmail($name,$last_subscription_id,$email)
    {
        //Send subscription email to admin
        try {
            $message = get_static_option('user_subscription_purchase_admin_email_message') ?? __('A user just purchase a subscription.');
            $message = str_replace(["@name","@subscription_id"],[$name, $last_subscription_id], $message);
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_admin_email_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {

        }

        //Send subscription email to user
        try {
            $message = get_static_option('user_subscription_purchase_message') ?? __('Your subscription purchase successfully completed.');
            $message = str_replace(["@name","@subscription_id"],[$name, $last_subscription_id], $message);
            Mail::to($email)->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {

        }
    }

    //admin notification
    private function adminNotification($last_subscription_id,$user_id)
    {
        AdminNotification::create([
            'identity'=>$last_subscription_id,
            'user_id'=>$user_id,
            'type'=>__('Buy Subscription'),
            'message'=>__('User subscription purchase'),
        ]);
    }
}
