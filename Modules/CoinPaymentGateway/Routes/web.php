<?php


use Modules\CoinPaymentGateway\App\Http\Controllers\CoinPaymentIpnController;

Route::post('frontend/payments/coinpayments-ipn/success/payment',[CoinPaymentIpnController::class,'coinpayment_ipn_for_all'])->name('coinpayment.ipn.all');

