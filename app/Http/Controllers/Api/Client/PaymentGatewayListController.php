<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentGatewayListController extends Controller
{
    public function payment_gateway_list()
    {
        $paypal_logo = get_attachment_image_by_id(get_static_option('paypal_preview_logo'));
        $paytm_logo = get_attachment_image_by_id(get_static_option('paytm_preview_logo'));
        $razorpay_logo = get_attachment_image_by_id(get_static_option('razorpay_preview_logo'));
        $stripe_logo = get_attachment_image_by_id(get_static_option('stripe_preview_logo'));
        $paystack_logo = get_attachment_image_by_id(get_static_option('paystack_preview_logo'));
        $mollie_logo = get_attachment_image_by_id(get_static_option('mollie_preview_logo'));
        $flutterwave_logo = get_attachment_image_by_id(get_static_option('flutterwave_preview_logo'));
        $midtrans_logo = get_attachment_image_by_id(get_static_option('midtrans_preview_logo'));
        $payfast_logo = get_attachment_image_by_id(get_static_option('payfast_preview_logo'));
        $cashfree_logo = get_attachment_image_by_id(get_static_option('cashfree_preview_logo'));
        $instamojo_logo = get_attachment_image_by_id(get_static_option('instamojo_preview_logo'));
        $marcadopago_logo = get_attachment_image_by_id(get_static_option('marcadopago_preview_logo'));
        $zitopay_logo = get_attachment_image_by_id(get_static_option('zitopay_preview_logo'));
        $billplz_logo = get_attachment_image_by_id(get_static_option('billplz_preview_logo'));
        $paytabs_logo = get_attachment_image_by_id(get_static_option('paytabs_preview_logo'));
        $cinetpay_logo = get_attachment_image_by_id(get_static_option('cinetpay_preview_logo'));
        $squareup_logo = get_attachment_image_by_id(get_static_option('squareup_preview_logo'));
        $toyyibpay_logo = get_attachment_image_by_id(get_static_option('toyyibpay_preview_logo'));
        $pagali_logo = get_attachment_image_by_id(get_static_option('pagali_preview_logo'));
        $authorize_dot_net_logo = get_attachment_image_by_id(get_static_option('authorize_dot_net_preview_logo'));
        $sitesway_logo = get_attachment_image_by_id(get_static_option('sitesway_preview_logo'));
        $iyzipay_logo = get_attachment_image_by_id(get_static_option('iyzipay_preview_logo'));
        $manual_payment_logo = get_attachment_image_by_id(get_static_option('manual_payment_preview_logo'));

        $data = [];
        if (get_static_option('paypal_gateway') === 'on'){
            $data['paypal'] =[
                'preview_logo' => $paypal_logo['img_url'],
                'sandbox_client_id' => get_static_option('paypal_sandbox_client_id'),
                'sandbox_client_secret' => get_static_option('paypal_sandbox_client_secret'),
                'sandbox_app_id' => get_static_option('paypal_sandbox_app_id'),
                'live_app_id' => get_static_option('paypal_live_app_id'),
                'payment_action' => get_static_option('paypal_payment_action'),
                'currency' => get_static_option('paypal_currency'),
                'notify_url' => get_static_option('paypal_notify_url'),
                'locale' => get_static_option('paypal_locale'),
                'validate_ssl' => get_static_option('paypal_validate_ssl'),
                'live_client_id' => get_static_option('paypal_live_client_id'),
                'live_client_secret' => get_static_option('paypal_live_client_secret'),
                'gateway' => get_static_option('paypal_gateway'),
                'test_mode' => get_static_option('paypal_test_mode'),
            ];
        }
        if (get_static_option('paytm_gateway') === 'on'){
            $data['paytm'] =[
                'gateway' =>get_static_option('paytm_gateway'),
                'preview_logo' =>$paytm_logo['img_url'],
                'merchant_key' =>get_static_option('paytm_merchant_key'),
                'merchant_mid' =>get_static_option('paytm_merchant_mid'),
                'merchant_website' =>get_static_option('paytm_merchant_website'),
                'test_mode' =>get_static_option('paytm_test_mode'),
                'channel' =>get_static_option('paytm_channel'),
                'industry_type' =>get_static_option('paytm_industry_type'),
            ];
        }
        if (get_static_option('razorpay_gateway') === 'on'){
            $data['razorpay'] =[
                'preview_logo' =>$razorpay_logo['img_url'],
                'key' =>get_static_option('razorpay_key'),
                'secret' =>get_static_option('razorpay_secret'),
                'api_key' =>get_static_option('razorpay_api_key'),
                'api_secret' =>get_static_option('razorpay_api_secret'),
                'gateway' =>get_static_option('razorpay_gateway'),
                'test_mode' =>get_static_option('razorpay_test_mode'),
            ];
        }
        if (get_static_option('stripe_gateway') === 'on'){
            $data['stripe'] =[
                'preview_logo' =>$stripe_logo['img_url'],
                'publishable_key' => get_static_option('stripe_publishable_key'),
                'secret_key' => get_static_option('stripe_secret_key'),
                '_public_key' => get_static_option('stripe_public_key'),
                'gateway' => get_static_option('stripe_gateway'),
                'test_mode' => get_static_option('stripe_test_mode'),
            ];
        }
        if (get_static_option('paystack_gateway') === 'on'){
            $data['paystack'] =[
                'preview_logo' => $paystack_logo['img_url'],
                'merchant_email' => get_static_option('paystack_merchant_email'),
                'public_key' => get_static_option('paystack_public_key'),
                'secret_key' => get_static_option('paystack_secret_key'),
                'gateway' => get_static_option('paystack_gateway'),
                'test_mode' => get_static_option('paystack_test_mode'),
            ];
        }
        if (get_static_option('mollie_gateway') === 'on'){
            $data['mollie'] =[ 'preview_logo' => $mollie_logo['img_url'],
                'public_key' => get_static_option('mollie_public_key'),
                'gateway' => get_static_option('mollie_gateway'),
                'test_mode' => get_static_option('mollie_test_mode'),
                ];
        }


        if (get_static_option('flutterwave_gateway') === 'on'){
            $data['flutterwave'] =[
                'preview_logo' => $flutterwave_logo['img_url'],
                'gateway' =>get_static_option('flutterwave_gateway'),
                'public_key' =>get_static_option('flw_public_key'),
                'secret_key' =>get_static_option('flw_secret_key'),
                'secret_hash' =>get_static_option('flw_secret_hash'),
                'test_mode' =>get_static_option('flutterwave_test_mode'),
                ];
        }

        if (get_static_option('midtrans_gateway') === 'on'){
            $data['midtrans'] =[
                'preview_logo' => $midtrans_logo['img_url'],
                'merchant_id' =>get_static_option('midtrans_merchant_id'),
                'server_key' =>get_static_option('midtrans_server_key'),
                'client_key' =>get_static_option('midtrans_client_key'),
                'environment' =>get_static_option('midtrans_environment'),
                'gateway' =>get_static_option('midtrans_gateway'),
                'test_mode' =>get_static_option('midtrans_test_mode'),
            ];
        }
        if (get_static_option('payfast_gateway') === 'on'){
            $data['payfast'] =[
                'preview_logo' => $payfast_logo['img_url'],
                'merchant_id' =>get_static_option('payfast_merchant_id'),
                'merchant_key' =>get_static_option('payfast_merchant_key'),
                'passphrase' =>get_static_option('payfast_passphrase'),
                'merchant_env' =>get_static_option('payfast_merchant_env'),
                'itn_url' =>get_static_option('payfast_itn_url'),
                'gateway' =>get_static_option('payfast_gateway'),
                'test_mode' =>get_static_option('payfast_test_mode'),
            ];
        }
        if (get_static_option('cashfree_gateway') === 'on'){
            $data['cashfree'] =[
                'preview_logo' => $cashfree_logo['img_url'],
                'test_mode' =>get_static_option('cashfree_test_mode'),
                'app_id' =>get_static_option('cashfree_app_id'),
                'secret_key' =>get_static_option('cashfree_secret_key'),
                'gateway' =>get_static_option('cashfree_gateway'),
            ];
        }
        if (get_static_option('instamojo_gateway') === 'on'){
            $data['instamojo'] =[
                'preview_logo' => $instamojo_logo['img_url'],
                'client_id' =>get_static_option('instamojo_client_id'),
                'client_secret' =>get_static_option('instamojo_client_secret'),
                'username' =>get_static_option('instamojo_username'),
                'password' =>get_static_option('instamojo_password'),
                'test_mode' =>get_static_option('instamojo_test_mode'),
                'gateway' =>get_static_option('instamojo_gateway'),
            ];
        }
        if (get_static_option('marcadopago_gateway') === 'on'){
            $data['marcadopago'] =[
                'preview_logo' => $marcadopago_logo['img_url'],
                'client_id' =>get_static_option('marcadopago_client_id'),
                'client_secret' =>get_static_option('marcadopago_client_secret'),
                'gateway' =>get_static_option('marcadopago_gateway'),
                'test_mode' =>get_static_option('marcadopago_test_mode'),
            ];
        }
        if (get_static_option('zitopay_gateway') === 'on'){
            $data['zitopay'] =[
                'preview_logo' => $zitopay_logo['img_url'],
                'username' =>get_static_option('zitopay_username'),
                'gateway' =>get_static_option('zitopay_gateway'),
                'test_mode' =>get_static_option('zitopay_test_mode'),
            ];
        }
        if (get_static_option('billplz_gateway') === 'on'){
            $data['billplz'] =[
                'preview_logo' => $billplz_logo['img_url'],
                'collection_name' =>get_static_option('billplz_collection_name'),
                'xsignature' =>get_static_option('billplz_xsignature'),
                'key' =>get_static_option('billplz_key'),
                'gateway' =>get_static_option('billplz_gateway'),
                'test_mode' =>get_static_option('billplz_test_mode'),
            ];
        }

        if (get_static_option('paytabs_gateway') === 'on'){
            $data['paytabs'] =[
                'preview_logo' => $paytabs_logo['img_url'],
                'region' =>get_static_option('paytabs_region'),
                'profile_id' =>get_static_option('paytabs_profile_id'),
                'server_key' =>get_static_option('paytabs_server_key'),
                'gateway' =>get_static_option('paytabs_gateway'),
                'test_mode' =>get_static_option('paytabs_test_mode'),
            ];
        }

        if (get_static_option('cinetpay_gateway') === 'on'){
            $data['cinetpay'] =[
                'preview_logo' => $cinetpay_logo['img_url'],
                'site_id' =>get_static_option('cinetpay_site_id'),
                'app_key' =>get_static_option('cinetpay_app_key'),
                'gateway' =>get_static_option('cinetpay_gateway'),
                'test_mode' =>get_static_option('cinetpay_test_mode'),
            ];
        }
        if (get_static_option('squareup_gateway') === 'on'){
            $data['squareup'] =[
                'preview_logo' => $squareup_logo['img_url'],
                'application_id' =>get_static_option('squareup_application_id'),
                'location_id' =>get_static_option('squareup_location_id'),
                'access_token' =>get_static_option('squareup_access_token'),
                'gateway' =>get_static_option('squareup_gateway'),
                'test_mode' =>get_static_option('squareup_test_mode'),
            ];
        }

        if (get_static_option('toyyibpay_gateway') === 'on'){
            $data['toyyibpay'] =[
                'preview_logo' => $toyyibpay_logo['img_url'],
                'secrect_key' =>get_static_option('toyyibpay_secrect_key'),
                'category_code' =>get_static_option('toyyibpay_category_code'),
                'gateway' =>get_static_option('toyyibpay_gateway'),
                'test_mode' =>get_static_option('toyyibpay_test_mode'),
            ];
        }

        if (get_static_option('pagali_gateway') === 'on'){
            $data['pagali'] =[
                'preview_logo' => $pagali_logo['img_url'],
                'page_id' =>get_static_option('pagali_page_id'),
                'entity_id' =>get_static_option('pagali_entity_id'),
                'gateway' =>get_static_option('pagali_gateway'),
                'test_mode' =>get_static_option('pagali_test_mode'),
            ];
        }

        if (get_static_option('authorize_dot_net_gateway') === 'on'){
            $data['authorize_dot_net'] =[
                'preview_logo' => $authorize_dot_net_logo['img_url'],
                'login_id' =>get_static_option('authorize_dot_net_login_id'),
                'transaction_id' =>get_static_option('authorize_dot_net_transaction_id'),
                'gateway' =>get_static_option('authorize_dot_net_gateway'),
                'test_mode' =>get_static_option('authorize_dot_net_test_mode'),
            ];
        }

        if (get_static_option('authorize_dot_net_gateway') === 'on'){
            $data['authorize_dot_net'] =[
                'preview_logo' => $authorize_dot_net_logo['img_url'],
                'login_id' =>get_static_option('authorize_dot_net_login_id'),
                'transaction_id' =>get_static_option('authorize_dot_net_transaction_id'),
                'gateway' =>get_static_option('authorize_dot_net_gateway'),
                'test_mode' =>get_static_option('authorize_dot_net_test_mode'),
            ];
        }

        if (get_static_option('sitesway_gateway') === 'on'){
            $data['sitesway'] =[
                'preview_logo' => $sitesway_logo['img_url'],
                'brand_id' =>get_static_option('sitesway_brand_id'),
                'api_key' =>get_static_option('sitesway_api_key'),
                'gateway' =>get_static_option('sitesway_gateway'),
                'test_mode' =>get_static_option('sitesway_test_mode'),
            ];
        }

        if (get_static_option('iyzipay_gateway') === 'on'){
            $data['iyzipay'] =[
                'preview_logo' => $iyzipay_logo['img_url'],
                'secret_key' =>get_static_option('iyzipay_secret_key'),
                'api_key' =>get_static_option('iyzipay_api_key'),
                'gateway' =>get_static_option('iyzipay_gateway'),
                'test_mode' =>get_static_option('iyzipay_test_mode'),
            ];
        }

        if (get_static_option('manual_payment_gateway') === 'on'){
            $data['manual_payment'] =[
                'preview_logo' => $manual_payment_logo['img_url'],
                'manual_payment_gateway' =>get_static_option('manual_payment_gateway'),
                'manual_payment_test_mode' =>get_static_option('manual_payment_test_mode'),
                'manual_payment_gateway_name' =>get_static_option('manual_payment_gateway_name'),
                'site_manual_payment_description' =>get_static_option('site_manual_payment_description'),
            ];
        }

        return response()->json($data);
    }
}
