<?php

namespace Modules\PaymentGatewaySettings\Http\Controllers;

use App\Helper\ModuleMetaData;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySettingsController extends Controller
{
    public function payment_info(Request $request)
    {
        if($request->isMethod('post')){
            update_static_option('site_global_currency', $request->site_global_currency);
            update_static_option('enable_disable_decimal_point', $request->enable_disable_decimal_point);
            update_static_option('site_currency_symbol_position', $request->site_currency_symbol_position);
            update_static_option('site_currency_thousand_separator', $request->site_currency_thousand_separator);
            update_static_option('site_currency_decimal_separator', $request->site_currency_decimal_separator);
            update_static_option('site_default_payment_gateway', $request->site_default_payment_gateway);
            $global_currency = get_static_option('site_global_currency');

            $field_rules['site_' . strtolower($global_currency) . '_to_usd_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_bdt_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_idr_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_inr_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_ngn_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_zar_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_brl_exchange_rate'] = 0;
            $field_rules['site_' . strtolower($global_currency) . '_to_myr_exchange_rate'] = 0;

            foreach ($field_rules as $item => $rule) {
                update_static_option($item, $request->$item);
            }

            toastr_success(__('Payment Gateway Info Updated Successfully.'));
            return back();
        }
        return view('paymentgatewaysettings::payment-info');
    }

    public function payment_gateway(Request $request)
    {
        if($request->isMethod('post')){
            $field_rules = [
                // paypal
                'paypal_preview_logo' => 'required|string|max:191',
                'paypal_sandbox_client_id' => 'nullable|string|max:191',
                'paypal_sandbox_client_secret' => 'nullable|string|max:191',
                'paypal_sandbox_app_id' => 'nullable|string|max:191',
                'paypal_live_app_id' => 'nullable|string|max:191',
                'paypal_payment_action' => 'nullable|string|max:191',
                'paypal_currency' => 'nullable|string|max:191',
                'paypal_notify_url' => 'nullable|string|max:191',
                'paypal_locale' => 'nullable|string|max:191',
                'paypal_validate_ssl' => 'nullable|string|max:191',
                'paypal_live_client_id' => 'nullable|string|max:191',
                'paypal_live_client_secret' => 'nullable|string|max:191',
                'paypal_gateway' => 'nullable|string|max:191',
                'paypal_test_mode' => 'nullable|string|max:191',
                // paytm
                'paytm_gateway' => 'nullable|string|max:191',
                'paytm_preview_logo' => 'nullable|string|max:191',
                'paytm_merchant_key' => 'nullable|string|max:191',
                'paytm_merchant_mid' => 'nullable|string|max:191',
                'paytm_merchant_website' => 'nullable|string|max:191',
                'paytm_test_mode' => 'nullable|string|max:191',
                'paytm_channel' => 'nullable|string|max:191',
                'paytm_industry_type' => 'nullable|string|max:191',
                // razorpay
                'razorpay_preview_logo' => 'nullable|string|max:191',
                'razorpay_key' => 'nullable|string|max:191',
                'razorpay_secret' => 'nullable|string|max:191',
                'razorpay_api_key' => 'nullable|string|max:191',
                'razorpay_api_secret' => 'nullable|string|max:191',
                'razorpay_gateway' => 'nullable|string|max:191',
                'razorpay_test_mode' => 'nullable|string|max:191',

                // stripe
                'stripe_preview_logo' => 'nullable|string|max:191',
                'stripe_publishable_key' => 'nullable|string|max:191',
                'stripe_secret_key' => 'nullable|string|max:191',
                'stripe_public_key' => 'nullable|string|max:191',
                'stripe_gateway' => 'nullable|string|max:191',
                'stripe_test_mode' => 'nullable|string|max:191',
                // paystack
                'paystack_merchant_email' => 'nullable|string|max:191',
                'paystack_preview_logo' => 'nullable|string|max:191',
                'paystack_public_key' => 'nullable|string|max:191',
                'paystack_secret_key' => 'nullable|string|max:191',
                'paystack_gateway' => 'nullable|string|max:191',
                'paystack_test_mode' => 'nullable|string|max:191',
                // mollie
                'mollie_preview_logo' => 'nullable|string|max:191',
                'mollie_public_key' => 'nullable|string|max:191',
                'mollie_gateway' => 'nullable|string|max:191',
                'mollie_test_mode' => 'nullable|string|max:191',
                // cash on delivery (COD)
                'cash_on_delivery_gateway' => 'nullable|string|max:191',
                'cash_on_delivery_preview_logo' => 'nullable|string|max:191',
                // flutterwave
                'flutterwave_preview_logo' => 'nullable|string|max:191',
                'flutterwave_gateway' => 'nullable|string|max:191',
                'flw_public_key' => 'nullable|string|max:191',
                'flw_secret_key' => 'nullable|string|max:191',
                'flw_secret_hash' => 'nullable|string|max:191',
                'flutterwave_test_mode' => 'nullable|string|max:191',
                // midtrans
                'midtrans_preview_logo' => 'nullable|string|max:191',
                'midtrans_merchant_id' => 'nullable|string|max:191',
                'midtrans_server_key' => 'nullable|string|max:191',
                'midtrans_client_key' => 'nullable|string|max:191',
                'midtrans_environment' => 'nullable|string|max:191',
                'midtrans_gateway' => 'nullable|string|max:191',
                'midtrans_test_mode' => 'nullable|string|max:191',
                // payfast
                'payfast_preview_logo' => 'nullable|string|max:191',
                'payfast_merchant_id' => 'nullable|string|max:191',
                'payfast_merchant_key' => 'nullable|string|max:191',
                'payfast_passphrase' => 'nullable|string|max:191',
                'payfast_merchant_env' => 'nullable|string|max:191',
                'payfast_itn_url' => 'nullable|string|max:191',
                'payfast_gateway' => 'nullable|string|max:191',
                'payfast_test_mode' => 'nullable|string|max:191',

                // cashfree
                'cashfree_preview_logo' => 'nullable|string|max:191',
                'cashfree_test_mode' => 'nullable|string|max:191',
                'cashfree_app_id' => 'nullable|string|max:191',
                'cashfree_secret_key' => 'nullable|string|max:191',
                'cashfree_gateway' => 'nullable|string|max:191',
                // instamojo
                'instamojo_preview_logo' => 'nullable|string|max:191',
                'instamojo_client_id' => 'nullable|string|max:191',
                'instamojo_client_secret' => 'nullable|string|max:191',
                'instamojo_username' => 'nullable|string|max:191',
                'instamojo_password' => 'nullable|string|max:191',
                'instamojo_test_mode' => 'nullable|string|max:191',
                'instamojo_gateway' => 'nullable|string|max:191',
                // marcadopago
                'marcadopago_preview_logo' => 'nullable|string|max:191',
                'marcadopago_client_id' => 'nullable|string|max:191',
                'marcadopago_client_secret' => 'nullable|string|max:191',
                'marcadopago_gateway' => 'nullable|string|max:191',
                'marcadopago_test_mode' => 'nullable|string|max:191',


                // zitopay
                'zitopay_username' => 'nullable|string|max:191',
                'zitopay_preview_logo' => 'nullable|string|max:191',
                'zitopay_gateway' => 'nullable|string|max:191',
                'zitopay_test_mode' => 'nullable|string|max:191',

                // billplz
                'billplz_collection_name' => 'nullable|string|max:191',
                'billplz_xsignature' => 'nullable|string|max:191',
                'billplz_key' => 'nullable|string|max:191',
                'billplz_preview_logo' => 'nullable|string|max:191',
                'billplz_gateway' => 'nullable|string|max:191',
                'billplz_test_mode' => 'nullable|string|max:191',

                // paytabs
                'paytabs_region' => 'nullable|string|max:191',
                'paytabs_profile_id' => 'nullable|string|max:191',
                'paytabs_server_key' => 'nullable|string|max:191',
                'paytabs_preview_logo' => 'nullable|string|max:191',
                'paytabs_gateway' => 'nullable|string|max:191',
                'paytabs_test_mode' => 'nullable|string|max:191',

                // cinetpay
                'cinetpay_site_id' => 'nullable|string|max:191',
                'cinetpay_app_key' => 'nullable|string|max:191',
                'cinetpay_preview_logo' => 'nullable|string|max:191',
                'cinetpay_gateway' => 'nullable|string|max:191',
                'cinetpay_test_mode' => 'nullable|string|max:191',

                // squareup
                'squareup_application_id' => 'nullable|string|max:191',
                'squareup_location_id' => 'nullable|string|max:191',
                'squareup_access_token' => 'nullable|string|max:191',
                'squareup_preview_logo' => 'nullable|string|max:191',
                'squareup_gateway' => 'nullable|string|max:191',
                'squareup_test_mode' => 'nullable|string|max:191',

                // toyyibpay
                'toyyibpay_secrect_key' => 'nullable|string|max:191',
                'toyyibpay_category_code' => 'nullable|string|max:191',
                'toyyibpay_preview_logo' => 'nullable|string|max:191',
                'toyyibpay_gateway' => 'nullable|string|max:191',
                'toyyibpay_test_mode' => 'nullable|string|max:191',

                // pagali
                'pagali_page_id' => 'nullable|string|max:191',
                'pagali_entity_id' => 'nullable|string|max:191',
                'pagali_preview_logo' => 'nullable|string|max:191',
                'pagali_gateway' => 'nullable|string|max:191',
                'pagali_test_mode' => 'nullable|string|max:191',

                // authorize dot net
                'authorize_dot_net_login_id' => 'nullable|string|max:191',
                'authorize_dot_net_transaction_id' => 'nullable|string|max:191',
                'authorize_dot_net_preview_logo' => 'nullable|string|max:191',
                'authorize_dot_net_gateway' => 'nullable|string|max:191',
                'authorize_dot_net_test_mode' => 'nullable|string|max:191',

                // sitesway
                'sitesway_brand_id' => 'nullable|string|max:191',
                'sitesway_api_key' => 'nullable|string|max:191',
                'sitesway_preview_logo' => 'nullable|string|max:191',
                'sitesway_gateway' => 'nullable|string|max:191',
                'sitesway_test_mode' => 'nullable|string|max:191',

                // iyzipay
                'iyzipay_secret_key' => 'nullable|string|max:191',
                'iyzipay_api_key' => 'nullable|string|max:191',
                'iyzipay_preview_logo' => 'nullable|string|max:191',
                'iyzipay_gateway' => 'nullable|string|max:191',
                'iyzipay_test_mode' => 'nullable|string|max:191',

                // kineticpay
                'kineticpay_gateway' => 'nullable|string|max:255',
                'kineticpay_test_mode' => 'nullable|string|max:255',
                'kineticpay_merchant_key' => 'nullable|string|max:255',
                'kineticpay_preview_logo' => 'nullable|string|max:255',

                // awdpay
                'awdpay_gateway' => 'nullable|string|max:255',
                'awdpay_test_mode' => 'nullable|string|max:255',
                'awdpay_private_key' => 'nullable|string|max:255',
                'awdpay_preview_logo' => 'nullable|string|max:255',
                'awdpay_logo_url' => 'nullable|string|max:255',

                // sslcommerce
                'sslcommerce_gateway' => 'nullable|string|max:255',
                'sslcommerce_preview_logo' => 'nullable|string|max:255',
                'sslcommerce_test_mode' => 'nullable|string|max:255',
                'sslcommerce_store_id' => 'nullable|string|max:255',
                'sslcommerce_store_password' => 'nullable|string|max:255',

                //yoomoney
                'yoomoney_gateway' => 'nullable|string|max:255',
                'yoomoney_test_mode' => 'nullable|string|max:255',
                'yoomoney_preview_logo' => 'nullable|string|max:255',
                'yoomoney_shop_id' => 'nullable|string|max:255',
                'yoomoney_secret_key' => 'nullable|string|max:255',

                //coin payments
                'coinpayments_gateway' => 'nullable|string|max:255',
                'coinpayments_test_mode' => 'nullable|string|max:255',
                'coinpayments_preview_logo' => 'nullable|string|max:255',
                'coinpayments_merchant' => 'nullable|string|max:255',
                'coinpayments_ipn_pin' => 'nullable|string|max:255',

                // manual payment
                'manual_payment_preview_logo' => 'nullable|string|max:191',
                'manual_payment_gateway' => 'nullable|string|max:191',
                'manual_payment_test_mode' => 'nullable|string|max:191',
                'manual_payment_gateway_name' => 'nullable|string|max:191',
                'site_manual_payment_description' => 'nullable|string|max:100000',
                ];

            $request->validate($field_rules);

            $saveAllPaymentGatewaySettings = (new ModuleMetaData())->saveAllPaymentGatewaySettings();
            foreach ($saveAllPaymentGatewaySettings as $pay_settings){
                foreach ($pay_settings as $pset){
                    if (empty($pset)){continue;}
                    update_static_option($pset, $request->$pset);
                }
            }

            foreach ($field_rules as $item => $rule) {
                update_static_option($item, $request->$item);
            }

            if(moduleExists('CoinPaymentGateway')){
                $currency_list_json = json_encode($request->coinpay_currency);
                DB::table('static_options')->updateOrInsert(
                    ['option_name' => 'coinpay_currency'],
                    ['option_value' => $currency_list_json]
                );
            }

            toastr_success(__('Payment Gateway Updated Successfully.'));
            return back();
        }
        return view('paymentgatewaysettings::payment-gateway');
    }
}
