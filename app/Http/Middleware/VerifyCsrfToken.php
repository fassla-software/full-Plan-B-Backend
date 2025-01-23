<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'frontend/payments/yoomoney-ipn/success/payment',
        'frontend/payments/coinpayments-ipn/success/payment',
        'frontend/payments/sslcommerce-ipn/success/payment',
        'order/kineticpay-ipn',


         //client deposit
        'client/wallet/payfast-ipn',
        'client/wallet/cashfree-ipn',
        'client/wallet/zitopay-ipn',
        'client/wallet/toyyibpay-ipn',
        'client/wallet/pagali-ipn',
        'client/wallet/siteways-ipn',
        'client/wallet/iyzipay-ipn',
        'client/wallet/kineticpay-ipn',
        'client/wallet/yoomoney-ipn',

        //freelancer deposit
        'freelancer/wallet/payfast-ipn',
        'freelancer/wallet/cashfree-ipn',
        'freelancer/wallet/zitopay-ipn',
        'freelancer/wallet/toyyibpay-ipn',
        'freelancer/wallet/pagali-ipn',
        'freelancer/wallet/siteways-ipn',
        'freelancer/wallet/iyzipay-ipn',
        'freelancer/wallet/kineticpay-ipn',
    ];
}
