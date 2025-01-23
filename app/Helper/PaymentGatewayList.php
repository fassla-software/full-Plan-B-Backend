<?php

namespace App\Helper;

use Modules\Wallet\Entities\Wallet;

class PaymentGatewayList
{
    public static function listOfPaymentGateways()
    {
        if(moduleExists('YooMoneyPaymentGateway')){
            $payment_gateway_list = ['manual_payment', 'paypal', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay', 'toyyibpay', 'pagali', 'authorize_dot_net', 'sitesway','iyzipay','kineticpay','awdpay','sslcommerce_gateway','yoomoney'];
        }elseif(moduleExists('CoinPaymentGateway')){
            $payment_gateway_list = ['manual_payment', 'paypal', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay', 'toyyibpay', 'pagali', 'authorize_dot_net', 'sitesway','iyzipay','kineticpay','awdpay','sslcommerce_gateway','coinpayments'];
        }
        else{
            $payment_gateway_list = ['manual_payment', 'paypal', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay', 'toyyibpay', 'pagali', 'authorize_dot_net', 'sitesway','iyzipay','kineticpay','awdpay','sslcommerce'];
        }
        // append payment gateway name from modules
//        $modules_payment_gateway = (new ModuleMetaData())->getAllPaymentGatewayList();
        $modules_payment_gateway = [];

        return ! empty($modules_payment_gateway) ? array_merge($payment_gateway_list, $modules_payment_gateway) : $payment_gateway_list;
    }

    public static function renderCurrentBalanceForm()
    {
        $output = '<div class="current-balance-wrapper">';
        $output .= '<input type="checkbox" name="selected_payment_gateway" id="current_balance_gateway" class="mr-2 current_balance_selected_gateway">';
        $output .= '<label for="current_balance_gateway">'.__('Deposit From Current Balance').'</label>';
        $output .= '</div>';

        return $output;
    }

    public static function renderWalletForm()
    {
        $auth_user_id = \Auth::guard('web')->user()->id;
        $wallet_lists = Wallet::where('user_id', $auth_user_id)->where('status', 1)->latest()->first();
        if (! empty($wallet_lists)) {
            $output = '<div class="wallet-payment-gateway-wrapper">';
            $output .= '<input type="checkbox" name="selected_payment_gateway" id="wallet_selected_payment_gateway" class="me-2 wallet_selected_payment_gateway">';
            $output .= '<label for="wallet_selected_payment_gateway">'.__('Use Wallet balance').'</label>';
            $output .= '</div>';
        } else {
            $output = '';
        }

        return $output;
    }

    public static function renderPaymentGatewayForForm($cash_on_delivery_show = true)
    {
        $output = '<div class="payment-gateway-wrapper payment_getway_image">';

        $output .= '<input type="hidden" name="selected_payment_gateway" id="order_from_user_wallet" value="'.get_static_option('site_default_payment_gateway').'">';

        $all_gateway = self::listOfPaymentGateways();
        $kineticpay_enable = 0;


        $output .= '<ul>';
        $cash_on_delivery = (bool) get_static_option('cash_on_delivery_gateway');
        if ($cash_on_delivery && $cash_on_delivery_show) {
            $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
            $output .= '</div></li>';
        }
        foreach ($all_gateway as $gateway) {
            if (! empty(get_static_option($gateway.'_gateway'))) {
                $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected active"' : '';

                $output .= '<li data-gateway="'.$gateway.'" '.$class.'><div class="img-select">';
                $output .= render_image_markup_by_attachment_id(get_static_option($gateway.'_preview_logo'));
                $output .= '</div></li>';
            }

            if($gateway == 'kineticpay'){
                $kineticpay_enable = 1;
            }
        }
        $output .= '</ul>';
        $output .= '</div>';


        if($kineticpay_enable == 1){
            $output .= '<div class="kinetic_payment_show_hide mt-4"> <div class="form-group kinetic_payment_field">
                            <div class="label">'.__('Choose Payment Method').'</div>
                            <select name="kineticpay_bank" id="kineticpay_bank" class="select" data-allow_clear="true" data-placeholder="Select Bank">
                                <option value="" selected="selected">Select Bank</option>
                                <option value="ABMB0212">Alliance Bank Malaysia Berhad</option>
                                <option value="ABB0233">Affin Bank Berhad</option>
                                <option value="AMBB0209">AmBank (M) Berhad</option>
                                <option value="BCBB0235">CIMB Bank Berhad</option>
                                <option value="BIMB0340">Bank Islam Malaysia Berhad</option>
                                <option value="BKRM0602">Bank Kerjasama Rakyat Malaysia Berhad</option>
                                <option value="BMMB0341">Bank Muamalat (Malaysia) Berhad</option>
                                <option value="BSN0601">Bank Simpanan Nasional Berhad</option>
                                <option value="CIT0219">Citibank Berhad</option>
                                <option value="HLB0224">Hong Leong Bank Berhad</option>
                                <option value="HSBC0223">HSBC Bank Malaysia Berhad</option>
                                <option value="KFH0346">Kuwait Finance House</option>
                                <option value="MB2U0227">Maybank2u / Malayan Banking Berhad</option>
                                <option value="MBB0228">Maybank2E / Malayan Banking Berhad E</option>
                                <option value="OCBC0229">OCBC Bank (Malaysia) Berhad</option>
                                <option value="PBB0233">Public Bank Berhad</option>
                                <option value="RHB0218">RHB Bank Berhad</option>
                                <option value="SCB0216">Standard Chartered Bank (Malaysia) Berhad</option>
                                <option value="UOB0226">United Overseas Bank (Malaysia) Berhad</option>
                            </select>
                        </div> </div>';
        }

        //extra field data for payment gateway
        $output .= '<div class="payment_gateway_extra_field_information_wrap">';
        if (! empty(get_static_option('manual_payment_gateway'))) {
            $output .= '<div class="manual_payment_gateway_extra_field"><div class="form-group"> <div class="label mt-3 mb-2">'.get_static_option('site_manual_payment_name').__('Receipt').'</div> <input type="file" name="manual_payment_image" class="form-control" style="line-height: 1.15"></div><div class="manual_description">'.get_static_option('site_manual_payment_description').'</div></div>';
        }
        $output .= '</div>';

        return $output;
    }
}
