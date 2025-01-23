<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\OrderMail;
use App\Models\AdminNotification;
use App\Models\JobProposal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class OrderIPNController extends Controller
{

    public function cancel_page()
    {
        return view('frontend.pages.order.cancel');
    }

    public function paypal_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');
            $this->update_database($order_id, $payment_data['transaction_id'], $user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function paytm_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');
            $this->update_database($order_id, $payment_data['transaction_id'], $user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function mollie_ipn_for_order()
    {
        $mollie_key = get_static_option('mollie_public_key');
        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey($mollie_key);
        $mollie->setEnv(get_static_option('mollie_test_mode') == 'on' ? true : false);
        $payment_data = $mollie->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');
            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function stripe_ipn_for_order()
    {
        $stripe = XgPaymentGateway::stripe();
        $stripe->setSecretKey(get_static_option('stripe_secret_key'));
        $stripe->setPublicKey(get_static_option('stripe_public_key'));
        $stripe->setEnv(get_static_option('stripe_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $stripe->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function razorpay_ipn_for_order()
    {
        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey(get_static_option('razorpay_api_key'));
        $razorpay->setApiSecret(get_static_option('razorpay_api_secret'));
        $razorpay->setEnv(get_static_option('razorpay_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $razorpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function flutterwave_ipn_for_order()
    {
        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey(get_static_option('flw_public_key'));
        $flutterwave->setSecretKey(get_static_option('flw_secret_key'));
        $flutterwave->setEnv(get_static_option('flutterwave_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $flutterwave->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function paystack_ipn_for_order()
    {
        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey(get_static_option('paystack_public_key') ?? '');
        $paystack->setSecretKey(get_static_option('paystack_secret_key') ?? '');
        $paystack->setMerchantEmail(get_static_option('paystack_merchant_email') ?? '');

        $payment_data = $paystack->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function cashfree_ipn_for_order()
    {
        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId(get_static_option('cashfree_app_id') ?? '');
        $cashfree->setSecretKey(get_static_option('cashfree_secret_key') ?? '');
        $cashfree->setEnv(get_static_option('cashfree_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $payment_data = $cashfree->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function instamojo_ipn_for_order()
    {
        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId(get_static_option('instamojo_client_id') ?? '');
        $instamojo->setSecretKey(get_static_option('instamojo_client_secret') ?? '');
        $instamojo->setEnv(get_static_option('instamojo_test_mode') == 'on' ? true : false);
        $payment_data = $instamojo->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function marcadopago_ipn_for_order()
    {

        $marcadopago = XgPaymentGateway::mercadopago();
        $marcadopago->setClientId(get_static_option('marcadopago_client_id') ?? '');
        $marcadopago->setClientSecret(get_static_option('marcadopago_client_secret') ?? '');
        $marcadopago->setEnv(get_static_option('marcadopago_test_mode') == 'on' ? true : false); ////true mean sandbox mode , false means live mode
        $payment_data = $marcadopago->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function payfast_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function midtrans_ipn_for_order()
    {
        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey(get_static_option('midtrans_client_key') ?? '');
        $midtrans->setServerKey(get_static_option('midtrans_server_key') ?? '');
        $midtrans->setEnv(get_static_option('midtrans_test_mode') == 'on' ? true : false); //true mean sandbox mode , false means live mode
        $payment_data = $midtrans->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'],$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order  successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function squareup_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function cinetpay_ipn_for_order()
    {
        $cinetpay = XgPaymentGateway::cinetpay();
        $cinetpay->setAppKey(get_static_option('cinetpay_app_key') ?? '');
        $cinetpay->setSiteId(get_static_option('cinetpay_site_id'));
        $cinetpay->setEnv(get_static_option('cinetpay_test_mode') == 'on' ? true : false);

        $payment_data = $cinetpay->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function paytabs_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function billplz_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function zitopay_ipn_for_order()
    {
        $zitopay = XgPaymentGateway::zitopay();
        $zitopay->setUsername(get_static_option('zitopay_username') ?? '');
        $zitopay->setEnv(get_static_option('zitopay_test_mode') == 'on' ? true : false);
        $payment_data = $zitopay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function toyyibpay_ipn_for_order()
    {
        $toyyibpay = XgPaymentGateway::toyyibpay();
        $toyyibpay->setUserSecretKey(get_static_option('toyyibpay_secrect_key') ?? '');
        $toyyibpay->setCategoryCode(get_static_option('toyyibpay_category_code') ?? '');
        $toyyibpay->setEnv(get_static_option('toyyibpay_test_mode') == 'on' ? true : false);
        $payment_data = $toyyibpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function authorizenet_ipn_for_order()
    {
        $authorizenet = XgPaymentGateway::authorizenet();
        $authorizenet->setMerchantLoginId(get_static_option('authorize_dot_net_login_id') ?? '');
        $authorizenet->setMerchantTransactionId(get_static_option('authorize_dot_net_transaction_id') ?? '');
        $authorizenet->setEnv(get_static_option('authorize_dot_net_test_mode') == 'on' ? true : false);

        $payment_data = $authorizenet->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function pagali_ipn_for_order()
    {
        $pagalipay = XgPaymentGateway::pagalipay();
        $pagalipay->setPageId(get_static_option('pagali_page_id') ?? '');
        $pagalipay->setEntityId(get_static_option('pagali_entity_id') ?? '');
        $pagalipay->setEnv(get_static_option('pagali_test_mode') == 'on' ? true : false);
        $payment_data = $pagalipay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }
    public function siteways_ipn_for_order()
    {
        $sitesway = XgPaymentGateway::sitesway();
        $sitesway->setBrandId(get_static_option('sitesway_brand_id') ?? '');
        $sitesway->setApiKey(get_static_option('sitesway_api_key') ?? '');
        $sitesway->setEnv(get_static_option('sitesway_test_mode') == 'on' ? true : false);
        $payment_data = $sitesway->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }

    public function iyzipay_ipn_for_order()
    {
        $iyzipay = XgPaymentGateway::iyzipay();
        $iyzipay->setSecretKey(get_static_option('iyzipay_secret_id') ?? '');
        $iyzipay->setApiKey(get_static_option('iyzipay_api_key') ?? '');
        $iyzipay->setEnv(get_static_option('iyzipay_test_mode') == 'on' ? true : false);
        $payment_data = $iyzipay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $user_id = session()->get('user_id');
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }

    public function kineticpay_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }

    public function awdpay_ipn_for_order()
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
            $freelancer_id = session()->get('freelancer_id');
            $project_or_job = session()->get('project_or_job');
            $proposal_id = session()->get('proposal_id_for_order');

            $this->update_database($order_id, $payment_data['transaction_id'] ,$user_id, $freelancer_id, $project_or_job, $proposal_id);
            $this->send_order_mail($order_id,$user_id,$freelancer_id);
            toastr_success('Order successfully completed');
            $new_order_id = getLastOrderId($order_id);
            return redirect()->route('order.user.success.page',$new_order_id);
        }
        return $this->cancel_page();
    }

    public function send_order_mail($last_order_id,$user_id,$freelancer_id)
    {
        if(empty($last_order_id)){ return redirect()->route('homepage');}

        $client = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();
        $freelancer = User::select(['id','first_name','last_name','email'])->where('id',$freelancer_id)->first();

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

    }
    private function update_database($last_order_id,$transaction_id,$user_id,$freelancer_id,$project_or_job,$proposal_id)
    {
        $order_info = Order::select('price','transaction_amount')->where('id',$last_order_id)->first();
        Order::where('id', $last_order_id)->where('user_id',$user_id)
            ->update([
                'price' => $order_info->price - $order_info->transaction_amount,
                'payment_status' => 'complete',
                'status' => 0,
                'transaction_id' => $transaction_id,
            ]);
        notificationToAdmin($last_order_id, $user_id,'Order',__('New order placed'));
        freelancer_notification($last_order_id, $freelancer_id,'Order',__('You have a new order'));

        //update job proposal (hired 0 to one) if the order created from job
        if($project_or_job == 'job'){
            JobProposal::where('id',$proposal_id)->update(['is_hired'=>1]);
        }

    }
}
