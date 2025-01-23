<?php

namespace Modules\Subscription\Http\Controllers\Frontend;

use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Subscription\Entities\UserSubscription;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class BuySubscriptionIPNController extends Controller
{
    protected function cancel_page()
    {
        return redirect()->route('subscriptions.buy.payment.cancel.static');
    }

    public function paypal_ipn_for_subscription()
    {
        $paypal = XgPaymentGateway::paypal();
        $paypal->setClientId(get_static_option('paypal_sandbox_client_id') ?? get_static_option('paypal_live_client_id')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setClientSecret(get_static_option('paypal_sandbox_client_secret') ?? get_static_option('paypal_live_client_secret')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setAppId(get_static_option('paypal_sandbox_app_id') ?? get_static_option('paypal_live_app_id')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setEnv(get_static_option('paypal_test_mode') === 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $paypal->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function paytm_ipn_for_subscription()
    {
        $paytm = XgPaymentGateway::paytm();
        $paytm->setMerchantId(get_static_option('paytm_merchant_mid'));
        $paytm->setMerchantKey(get_static_option('paytm_merchant_key'));
        $paytm->setMerchantWebsite(get_static_option('paytm_merchant_website') ?? 'WEBSTAGING');
        $paytm->setChannel(get_static_option('paytm_channel') ?? 'WEB');
        $paytm->setIndustryType(get_static_option('paytm_industry_type') ?? 'Retail');
        $paytm->setEnv(get_static_option('paytm_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $paytm->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function mollie_ipn_for_subscription()
    {
        $mollie_key = get_static_option('mollie_public_key');
        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey($mollie_key);
        $mollie->setEnv(get_static_option('mollie_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $mollie->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function stripe_ipn_for_subscription()
    {
        $stripe = XgPaymentGateway::stripe();
        $stripe->setSecretKey(get_static_option('stripe_secret_key'));
        $stripe->setPublicKey(get_static_option('stripe_public_key'));
        $stripe->setEnv(get_static_option('stripe_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $stripe->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function razorpay_ipn_for_subscription()
    {
        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey(get_static_option('razorpay_api_key'));
        $razorpay->setApiSecret(get_static_option('razorpay_api_secret'));
        $razorpay->setEnv(get_static_option('razorpay_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $razorpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function flutterwave_ipn_for_subscription()
    {
        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey(get_static_option('flw_public_key'));
        $flutterwave->setSecretKey(get_static_option('flw_secret_key'));
        $flutterwave->setEnv(get_static_option('flutterwave_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $flutterwave->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function paystack_ipn_for_subscription()
    {
        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey(get_static_option('paystack_public_key') ?? '');
        $paystack->setSecretKey(get_static_option('paystack_secret_key') ?? '');
        $paystack->setMerchantEmail(get_static_option('paystack_merchant_email') ?? '');

        $payment_data = $paystack->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function cashfree_ipn_for_subscription()
    {
        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId(get_static_option('cashfree_app_id') ?? '');
        $cashfree->setSecretKey(get_static_option('cashfree_secret_key') ?? '');
        $cashfree->setEnv(get_static_option('cashfree_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $cashfree->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function instamojo_ipn_for_subscription()
    {
        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId(get_static_option('instamojo_client_id') ?? '');
        $instamojo->setSecretKey(get_static_option('instamojo_client_secret') ?? '');
        $instamojo->setEnv(get_static_option('instamojo_test_mode') == 'on' ? true : false);
        $payment_data = $instamojo->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function marcadopago_ipn_for_subscription()
    {

        $marcadopago = XgPaymentGateway::mercadopago();
        $marcadopago->setClientId(get_static_option('marcadopago_client_id') ?? '');
        $marcadopago->setClientSecret(get_static_option('marcadopago_client_secret') ?? '');
        $marcadopago->setEnv(get_static_option('marcadopago_test_mode') == 'on' ? true : false); ////true mean sandbox mode , false means live mode
        $payment_data = $marcadopago->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function payfast_ipn_for_subscription()
    {
        $payfast = XgPaymentGateway::payfast();
        $payfast->setMerchantId(get_static_option('payfast_merchant_id' ?? ''));
        $payfast->setMerchantKey(get_static_option('payfast_merchant_key' ?? ''));
        $payfast->setPassphrase(get_static_option('payfast_passphrase' ?? ''));
        $payfast->setEnv(get_static_option('payfast_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $payfast->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function midtrans_ipn_for_subscription()
    {

        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey(get_static_option('midtrans_client_key') ?? '');
        $midtrans->setServerKey(get_static_option('midtrans_server_key') ?? '');
        $midtrans->setEnv(get_static_option('midtrans_test_mode') == 'on' ? true : false); //true mean sandbox mode , false means live mode
        $payment_data = $midtrans->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function squareup_ipn_for_subscription()
    {
        $squareup = XgPaymentGateway::squareup();
        $squareup->setLocationId(get_static_option('squareup_location_id') ?? '');
        $squareup->setAccessToken(get_static_option('squareup_access_token') ?? '');
        $squareup->setApplicationId(get_static_option('squareup_application_id') ?? '');
        $squareup->setEnv(get_static_option('squareup_test_mode') == 'on' ? true : false);
        $payment_data = $squareup->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function cinetpay_ipn_for_subscription()
    {
        $cinetpay = XgPaymentGateway::cinetpay();
        $cinetpay->setAppKey(get_static_option('cinetpay_app_key') ?? '');
        $cinetpay->setSiteId(get_static_option('cinetpay_site_id'));
        $cinetpay->setEnv(get_static_option('cinetpay_test_mode') == 'on' ? true : false);

        $payment_data = $cinetpay->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function paytabs_ipn_for_subscription()
    {
        $paytabs = XgPaymentGateway::paytabs();
        $paytabs->setProfileId(get_static_option('paytabs_profile_id') ?? '');
        $paytabs->setRegion(get_static_option('paytabs_region') ?? '');
        $paytabs->setServerKey(get_static_option('paytabs_server_key') ?? '');
        $paytabs->setEnv(get_static_option('paytabs_test_mode') == 'on' ? true : false);
        $payment_data = $paytabs->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function billplz_ipn_for_subscription()
    {
        $billplz = XgPaymentGateway::billplz();
        $billplz->setKey(get_static_option('billplz_key') ?? '');
        $billplz->setVersion('v4');
        $billplz->setXsignature(get_static_option('billplz_xsignature') ?? '');
        $billplz->setCollectionName(get_static_option('billplz_collection_name') ?? '');
        $billplz->setEnv(get_static_option('billplz_test_mode') == 'on' ? true : false);
        $payment_data = $billplz->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function zitopay_ipn_for_subscription()
    {
        $zitopay = XgPaymentGateway::zitopay();
        $zitopay->setUsername(get_static_option('zitopay_username') ?? '');
        $zitopay->setEnv(get_static_option('zitopay_test_mode') == 'on' ? true : false);
        $payment_data = $zitopay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function toyyibpay_ipn_for_subscription()
    {
        $toyyibpay = XgPaymentGateway::toyyibpay();
        $toyyibpay->setUserSecretKey(get_static_option('toyyibpay_secrect_key') ?? '');
        $toyyibpay->setCategoryCode(get_static_option('toyyibpay_category_code') ?? '');
        $toyyibpay->setEnv(get_static_option('toyyibpay_test_mode') == 'on' ? true : false);
        $payment_data = $toyyibpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function authorizenet_ipn_for_subscription()
    {
        $authorizenet = XgPaymentGateway::authorizenet();
        $authorizenet->setMerchantLoginId(get_static_option('authorize_dot_net_login_id') ?? '');
        $authorizenet->setMerchantTransactionId(get_static_option('authorize_dot_net_transaction_id') ?? '');
        $authorizenet->setEnv(get_static_option('authorize_dot_net_test_mode') == 'on' ? true : false);

        $payment_data = $authorizenet->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function pagali_ipn_for_subscription()
    {
        $pagalipay = XgPaymentGateway::pagalipay();
        $pagalipay->setPageId(get_static_option('pagali_page_id') ?? '');
        $pagalipay->setEntityId(get_static_option('pagali_entity_id') ?? '');
        $pagalipay->setEnv(get_static_option('pagali_test_mode') == 'on' ? true : false);
        $payment_data = $pagalipay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }
    public function siteways_ipn_for_subscription()
    {
        $sitesway = XgPaymentGateway::sitesway();
        $sitesway->setBrandId(get_static_option('sitesway_brand_id') ?? '');
        $sitesway->setApiKey(get_static_option('sitesway_api_key') ?? '');
        $sitesway->setEnv(get_static_option('sitesway_test_mode') == 'on' ? true : false);
        $payment_data = $sitesway->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }

    public function iyzipay_ipn_for_subscription()
    {
        $iyzipay = XgPaymentGateway::iyzipay();
        $iyzipay->setSecretKey(get_static_option('iyzipay_secret_id') ?? '');
        $iyzipay->setApiKey(get_static_option('iyzipay_api_key') ?? '');
        $iyzipay->setEnv(get_static_option('iyzipay_test_mode') == 'on' ? true : false);
        $payment_data = $iyzipay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }

    public function kineticpay_ipn_for_subscription()
    {
        $kineticpay = XgPaymentGateway::kineticpay();
        $kineticpay->setMerchantKey(get_static_option('kineticpay_merchant_key') ?? '');
        $kineticpay->setCurrency(self::globalCurrency());
        $kineticpay->setEnv(get_static_option('kineticpay_test_mode') == 'on' ? true : false); // this must be type of boolean , string will not work
        $kineticpay->setExchangeRate(self::usdConversionValue()); // if INR not set as currency
        $payment_data = $kineticpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }

    public function awdpay_ipn_for_subscription()
    {
        $awdpay = XgPaymentGateway::awdpay();
        $awdpay->setPrivateKey(get_static_option('awdpay_private_key') ?? '');
        $awdpay->setLogoUrl(get_static_option('awdpay_logo_url') ?? '');
        $awdpay->setEnv(get_static_option('awdpay_test_mode') == 'on' ? true : false);
        $awdpay->setCurrency(self::globalCurrency());
        $payment_data = $awdpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $user_type = session()->get('user_type');
            $this->update_database($order_id, $payment_data['transaction_id']);
            $this->send_jobs_mail($order_id,$user_id);
            toastr_success('Subscription purchase success');
            return redirect()->route($user_type.'.'.'subscriptions.all');
        }
        return $this->cancel_page();
    }


    public function send_jobs_mail($last_subscription_id,$user_id)
    {
        if(empty($last_subscription_id)){ return redirect()->route('homepage');}

        $user = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();

        //Send subscription email to admin
        try {
            $message = get_static_option('user_subscription_purchase_admin_email_message') ?? __('A user just purchase a subscription.');
            $message = str_replace(["@name","@subscription_id"],[$user->first_name.' '.$user->last_name, $last_subscription_id], $message);
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_admin_email_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        //Send subscription email to user
        try {
            $message = get_static_option('user_subscription_purchase_message') ?? __('Your subscription purchase successfully completed.');
            $message = str_replace(["@name","@subscription_id"],[$user->first_name.' '.$user->last_name, $last_subscription_id], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {}
    }
    private function update_database($last_subscription_id, $transaction_id)
    {
        $subscription_details = UserSubscription::find($last_subscription_id);
        UserSubscription::where('id', $last_subscription_id)->where('user_id',$subscription_details->user_id)
            ->update([
                'payment_status' => 'complete',
                'status' => 1,
                'transaction_id' => $transaction_id,
            ]);

        AdminNotification::create([
            'identity'=>$last_subscription_id,
            'user_id'=>$subscription_details->user_id,
            'type'=>__('Buy Subscription'),
            'message'=>__('User subscription purchase'),
        ]);
    }
}
