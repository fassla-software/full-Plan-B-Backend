<?php

namespace App\Helper;

use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class PaymentGatewayRequestHelper
{
    public static function paypal(){
        $paypal = XgPaymentGateway::paypal();
        $paypal->setClientId(get_static_option('paypal_sandbox_client_id') ?? get_static_option('paypal_live_client_id')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setClientSecret(get_static_option('paypal_sandbox_client_secret') ?? get_static_option('paypal_live_client_secret')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setAppId(get_static_option('paypal_sandbox_app_id') ?? get_static_option('paypal_live_app_id')); // provide sandbox id if payment env set to true, otherwise provide live credentials
        $paypal->setCurrency(self::globalCurrency());
        $paypal->setEnv(get_static_option('paypal_test_mode') === 'on' ? true : false); //env must set as boolean, string will not work
        $paypal->setExchangeRate(self::usdConversionValue()); // if INR not set as currency

        return $paypal;
    }

    public static function mollie(){
        $mollie_key = get_static_option('mollie_public_key');
        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey($mollie_key);
        $mollie->setCurrency(self::globalCurrency());
        $mollie->setEnv(true); //env must set as boolean, string will not work
        $mollie->setExchangeRate(self::usdConversionValue()); // if USD not set as currency

        return $mollie;
    }

    public static function paytm(){

        $paytm_merchant_id = getenv('PAYTM_MERCHANT_ID');
        $paytm_merchant_key = getenv('PAYTM_MERCHANT_KEY');
        $paytm_merchant_website = getenv('PAYTM_MERCHANT_WEBSITE') ?? 'WEBSTAGING';
        $paytm_channel = getenv('PAYTM_CHANNEL') ?? 'WEB';
        $paytm_industry_type = getenv('PAYTM_INDUSTRY_TYPE') ?? 'Retail';
        $paytm_env = getenv('PAYTM_ENVIRONMENT');

        $paytm = XgPaymentGateway::paytm();
        $paytm->setMerchantId($paytm_merchant_id);
        $paytm->setMerchantKey($paytm_merchant_key);
        $paytm->setMerchantWebsite($paytm_merchant_website);
        $paytm->setChannel($paytm_channel);
        $paytm->setIndustryType($paytm_industry_type);
        $paytm->setCurrency(self::globalCurrency());
        $paytm->setEnv($paytm_env === 'local'); // this must be type of boolean , string will not work
        $paytm->setExchangeRate(self::inrConversionValue()); // if INR not set as currency

        return $paytm;
    }

    public static function stripe(){

        $stripe = XgPaymentGateway::stripe();
        $stripe->setSecretKey(get_static_option('stripe_secret_key'));
        $stripe->setPublicKey(get_static_option('stripe_public_key'));
        if(moduleExists('CoinPaymentGateway')){
        $stripe->setCurrency('USD');
        }else{
            $stripe->setCurrency(self::globalCurrency());
        }
        $stripe->setEnv(get_static_option('stripe_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $stripe->setExchangeRate(self::usdConversionValue()); // if USD not set as currency
        return $stripe;
    }

    public static function razorpay(){
        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey(get_static_option('razorpay_api_key'));
        $razorpay->setApiSecret(get_static_option('razorpay_api_secret'));
        $razorpay->setCurrency(self::globalCurrency());
        $razorpay->setEnv(get_static_option('razorpay_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $razorpay->setExchangeRate(self::inrConversionValue()); // if INR not set as currency

        return $razorpay;
    }

    public static function flutterwave(){
        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey(get_static_option('flw_public_key'));
        $flutterwave->setSecretKey(get_static_option('flw_secret_key'));
        $flutterwave->setCurrency(self::globalCurrency());
        $flutterwave->setEnv(get_static_option('flutterwave_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $flutterwave->setExchangeRate(self::usdConversionValue()); // if NGN not set as currency
        return $flutterwave;
    }
    public static function paystack(){
        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey(get_static_option('paystack_public_key') ?? '');
        $paystack->setSecretKey(get_static_option('paystack_secret_key') ?? '');
        $paystack->setMerchantEmail(get_static_option('paystack_merchant_email') ?? '');
        $paystack->setEnv(get_static_option('paystack_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $paystack->setCurrency(self::globalCurrency());
        $paystack->setExchangeRate(self::ngnConversionValue()); // if NGN not set as currency

        return $paystack;
    }
    public static function sslcommerz(){
        $exchange_rate=self::bdtConversionValue();
        $sslcommerz = XgPaymentGateway::sslcommerz();
        $sslcommerz->setStoreId(get_static_option('sslcommerce_store_id') ?? '');
        $sslcommerz->setStorePasswd(get_static_option('sslcommerce_store_password') ?? '');
        $sslcommerz->setEnv(get_static_option('sslcommerce_test_mode') == 'on' ? true : false); // this must be type of boolean , string will not work
        $sslcommerz->setCurrency(self::globalCurrency());
        $sslcommerz->setExchangeRate($exchange_rate ? floatval($exchange_rate) : $exchange_rate ); // if BDT not set as currency

        return $sslcommerz;
    }


    public static function marcadopago(){
        $marcadopago = XgPaymentGateway::mercadopago();
        $marcadopago->setClientId(get_static_option('marcadopago_client_id') ?? '');
        $marcadopago->setClientSecret(get_static_option('marcadopago_client_secret') ?? '');
        $marcadopago->setCurrency(self::globalCurrency());
        $marcadopago->setExchangeRate(self::brlConversionValue()); // if BRL not set as currency, you must have to provide exchange rate for it
        $marcadopago->setEnv(get_static_option('marcadopago_test_mode') == 'on' ? true : false); ////true mean sandbox mode , false means live mode

        return $marcadopago;
    }
    public static function instamojo(){
        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId(get_static_option('instamojo_client_id') ?? '');
        $instamojo->setSecretKey(get_static_option('instamojo_client_secret') ?? '');
        $instamojo->setCurrency(self::globalCurrency());
        $instamojo->setEnv(get_static_option('instamojo_test_mode') == 'on' ? true : false);
        $instamojo->setExchangeRate(self::inrConversionValue()); // if INR not set as currency

        return $instamojo;
    }

    public static function cashfree()
    {
        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId(get_static_option('cashfree_app_id') ?? '');
        $cashfree->setSecretKey(get_static_option('cashfree_secret_key') ?? '');
        $cashfree->setCurrency(self::globalCurrency());
        $cashfree->setEnv(get_static_option('cashfree_test_mode') == 'on' ? true : false); //env must set as boolean, string will not work
        $cashfree->setExchangeRate(self::inrConversionValue()); // if INR not set as currency

        return $cashfree;
    }
    public static function payfast(){
        $payfast = XgPaymentGateway::payfast();
        $payfast->setMerchantId(get_static_option('payfast_merchant_id' ?? ''));
        $payfast->setMerchantKey(get_static_option('payfast_merchant_key' ?? ''));
        $payfast->setPassphrase(get_static_option('payfast_passphrase' ?? ''));
        $payfast->setCurrency(self::globalCurrency());
        $payfast->setEnv(get_static_option('payfast_test_mode') == 'on' ? true : false);
        $payfast->setExchangeRate(self::zarConversionValue()); // if ZAR not set as currency
        return $payfast;
    }
    public static function midtrans(){
        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey(get_static_option('midtrans_client_key') ?? '');
        $midtrans->setServerKey(get_static_option('midtrans_server_key') ?? '');
        $midtrans->setCurrency(self::globalCurrency());
        $midtrans->setEnv(get_static_option('midtrans_test_mode') == 'on' ? true : false); //true mean sandbox mode , false means live mode
        $midtrans->setExchangeRate(self::idrConversionValue()); // if IDR not set as currency
        return $midtrans;
    }

    public static function squareup(){
        $squareup = XgPaymentGateway::squareup();
        $squareup->setLocationId(get_static_option('squareup_location_id') ?? '');
        $squareup->setAccessToken(get_static_option('squareup_access_token') ?? '');
        $squareup->setApplicationId(get_static_option('squareup_application_id') ?? '');
        $squareup->setCurrency(self::globalCurrency());
        $squareup->setEnv(get_static_option('squareup_test_mode') == 'on' ? true : false);
        $squareup->setExchangeRate(self::usdConversionValue()); // if USD not set as currency
        return $squareup;
    }

    public static function cinetpay(){
        $cinetpay = XgPaymentGateway::cinetpay();
        $cinetpay->setAppKey(get_static_option('cinetpay_app_key') ?? '');
        $cinetpay->setSiteId(get_static_option('cinetpay_site_id'));
        $cinetpay->setCurrency(self::globalCurrency());
        $cinetpay->setEnv(get_static_option('cinetpay_test_mode') == 'on' ? true : false);
        $cinetpay->setExchangeRate(self::usdConversionValue()); // if ['XOF', 'XAF', 'CDF', 'GNF', 'USD'] not set as currency

        return $cinetpay;
    }

    public static function paytabs()
    {
        $paytabs = XgPaymentGateway::paytabs();
        $paytabs->setProfileId(get_static_option('paytabs_profile_id') ?? '');
        $paytabs->setRegion(get_static_option('paytabs_region') ?? '');
        $paytabs->setServerKey(get_static_option('paytabs_server_key') ?? '');
        $paytabs->setCurrency(self::globalCurrency());
        $paytabs->setEnv(get_static_option('paytabs_test_mode') == 'on' ? true : false);
        $paytabs->setExchangeRate(self::usdConversionValue()); // if ['AED','EGP','SAR','OMR','JOD','USD'] not set as currency
        return $paytabs;
    }

    public static function billplz(){
        $billplz = XgPaymentGateway::billplz();
        $billplz->setKey(get_static_option('billplz_key') ?? '');
        $billplz->setVersion('v4');
        $billplz->setXsignature(get_static_option('billplz_xsignature') ?? '');
        $billplz->setCollectionName(get_static_option('billplz_collection_name') ?? '');
        $billplz->setCurrency(self::globalCurrency());
        $billplz->setEnv(get_static_option('billplz_test_mode') == 'on' ? true : false);
        $billplz->setExchangeRate(self::myrConversionValue()); // if ['MYR'] not set as currency

        return $billplz;
    }

    public static function zitopay(){
        $zitopay = XgPaymentGateway::zitopay();
        $zitopay->setUsername(get_static_option('zitopay_username') ?? '');
        $zitopay->setCurrency(self::globalCurrency());
        $zitopay->setEnv(get_static_option('zitopay_test_mode') == 'on' ? true : false);
        $zitopay->setExchangeRate(self::usdConversionValue());
        return $zitopay;
    }

    public static function toyyibpay(){
        $toyyibpay = XgPaymentGateway::toyyibpay();
        $toyyibpay->setUserSecretKey(get_static_option('toyyibpay_secrect_key') ?? '');
        $toyyibpay->setCategoryCode(get_static_option('toyyibpay_category_code') ?? '');
        $toyyibpay->setEnv(get_static_option('toyyibpay_test_mode') == 'on' ? true : false);
        $toyyibpay->setCurrency(self::globalCurrency());
        $toyyibpay->setExchangeRate(self::myrConversionValue()); //only support MYR Currency
        return $toyyibpay;
    }

    public static function authorizenet(){
        $authorizenet = XgPaymentGateway::authorizenet();
        $authorizenet->setMerchantLoginId(get_static_option('authorize_dot_net_login_id') ?? '');
        $authorizenet->setMerchantTransactionId(get_static_option('authorize_dot_net_transaction_id') ?? '');
        $authorizenet->setEnv(get_static_option('authorize_dot_net_test_mode') == 'on' ? true : false);
        $authorizenet->setCurrency(self::globalCurrency());
        $authorizenet->setExchangeRate(self::inrConversionValue()); //only support MYR Currency
        return $authorizenet;
    }

    public static function pagalipay(){
        $pagalipay = XgPaymentGateway::pagalipay();
        $pagalipay->setPageId(get_static_option('pagali_page_id') ?? '');
        $pagalipay->setEntityId(get_static_option('pagali_entity_id') ?? '');
        $pagalipay->setEnv(get_static_option('pagali_test_mode') == 'on' ? true : false);
        $pagalipay->setCurrency(self::globalCurrency());
        $pagalipay->setExchangeRate(self::myrConversionValue()); //only support MYR Currency
        return $pagalipay;
    }

    public static function sitesway(){
        $sitesway = XgPaymentGateway::sitesway();
        $sitesway->setBrandId(get_static_option('sitesway_brand_id') ?? '');
        $sitesway->setApiKey(get_static_option('sitesway_api_key') ?? '');
        $sitesway->setEnv(get_static_option('sitesway_test_mode') == 'on' ? true : false);
        $sitesway->setCurrency(self::globalCurrency());
        return $sitesway;
    }

    public static function iyzipay(){
        $iyzipay = XgPaymentGateway::iyzipay();
        $iyzipay->setSecretKey(get_static_option('iyzipay_secret_key') ?? '');
        $iyzipay->setApiKey(get_static_option('iyzipay_api_key') ?? '');
        $iyzipay->setEnv(get_static_option('iyzipay_test_mode') == 'on' ? true : false);
        $iyzipay->setCurrency(self::globalCurrency());
        return $iyzipay;
    }

    public static function kineticpay(){
        $kineticpay = XgPaymentGateway::kineticpay();
        $kineticpay->setMerchantKey(get_static_option('kineticpay_merchant_key') ?? '');
        $kineticpay->setBank(request()->kineticpay_bank);
        $kineticpay->setCurrency(self::globalCurrency());
        $kineticpay->setEnv(get_static_option('kineticpay_test_mode') == 'on' ? true : false); // this must be type of boolean , string will not work
        $kineticpay->setExchangeRate(self::usdConversionValue()); // if INR not set as currency
        return $kineticpay;
    }

    public static function awdpay(){
        $awdpay = XgPaymentGateway::awdpay();
        $awdpay->setPrivateKey(get_static_option('awdpay_private_key') ?? '');
        $awdpay->setLogoUrl(get_static_option('awdpay_logo_url') ?? '');
        $awdpay->setEnv(get_static_option('awdpay_test_mode') == 'on' ? true : false);
        $awdpay->setCurrency(self::globalCurrency());
        return $awdpay;
    }

    public static function yoomoney(){
        $yoomoney = XgPaymentGateway::yoomoney();
        $yoomoney->setShopId(get_static_option('yoomoney_shop_id') ?? '');
        $yoomoney->setSecretKey(get_static_option('yoomoney_secret_key') ?? '');
        $yoomoney->setEnv(get_static_option('yoomoney_test_mode') == 'on' ? true : false);
        $yoomoney->setCurrency(self::globalCurrency());
        $yoomoney->setExchangeRate(self::usdConversionValue()); // if INR not set as currency
        return $yoomoney;
    }

    //coinpayments
    public static function coinpayments(){
        $coinpayment = XgPaymentGateway::coinpayments();
        $coinpayment->setMerchant(get_static_option('coinpayments_merchant') ?? '');
        $coinpayment->setCurrency(self::globalCurrency());
        $coinpayment->setAllowCurrencies(implode(',', json_decode(get_static_option('coinpay_currency'), true)));
        $coinpayment->setIpnPin(get_static_option('coinpayments_ipn_pin'));
        $coinpayment->setEnv(get_static_option('coinpayments_test_mode') == 'on' ? true : false);
        $coinpayment->setExchangeRate(self::usdConversionValue());
        return $coinpayment;
    }


    private static function globalCurrency()
    {
        return get_static_option('site_global_currency');
    }

    private static function usdConversionValue()
    {
        return get_static_option('site_' . strtolower(self::globalCurrency()) . '_to_usd_exchange_rate');
    }

    private static function bdtConversionValue()
    {
        return get_static_option('site_' . strtolower(self::globalCurrency()) . '_to_bdt_exchange_rate');
    }
    private static function inrConversionValue()
    {
        return get_static_option('site_usd_to_inr_exchange_rate');
    }
    private static function ngnConversionValue()
    {
        return get_static_option('site_usd_to_ngn_exchange_rate');
    }
    private static function zarConversionValue()
    {
        return get_static_option('site_usd_to_zar_exchange_rate');
    }
    private static function brlConversionValue()
    {
        return get_static_option('site_usd_to_brl_exchange_rate');
    }
    private static function idrConversionValue()
    {
        return get_static_option('site_usd_to_idr_exchange_rate');
    }
    private static function myrConversionValue()
    {
        return get_static_option('site_usd_to_myr_exchange_rate');
    }
}
