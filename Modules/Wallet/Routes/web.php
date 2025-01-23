<?php

Route::group(['middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::controller(\Modules\Wallet\Http\Controllers\Client\ClientWalletDepositController::class)->group(function () {
        Route::get('frontend/payments/paystack-ipn','paystack_ipn_for_all')->name('paystack.ipn.all');
        Route::post('frontend/payments/sslcommerce-ipn/success/payment','sslcommerce_ipn_for_all')->name('sslcommerce.ipn.all');
    });

});

Route::group(['prefix'=>'client/wallet','as'=>'client.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::controller(\Modules\Wallet\Http\Controllers\Client\WalletController::class)->group(function () {
        Route::get('history','wallet_history')->name('wallet.history');
        Route::get('paginate/data', 'pagination')->name('wallet.paginate.data');
        Route::get('search-history', 'search_history')->name('wallet.search');
        Route::post('deposit', 'deposit')->name('wallet.deposit');
        Route::get('deposit-cancel-static','deposit_payment_cancel_static')->name('wallet.deposit.payment.cancel.static');
    });

    Route::controller(\Modules\Wallet\Http\Controllers\Client\ClientWalletDepositController::class)->group(function () {
        Route::get('paypal-ipn','paypal_ipn_for_wallet')->name('paypal.ipn.wallet');
        Route::post('paytm-ipn','paytm_ipn_for_wallet')->name('paytm.ipn.wallet');
        Route::get('mollie/ipn','mollie_ipn_for_wallet')->name('mollie.ipn.wallet');
        Route::get('stripe/ipn','stripe_ipn_for_wallet')->name('stripe.ipn.wallet');
        Route::post('razorpay-ipn','razorpay_ipn_for_wallet')->name('razorpay.ipn.wallet');
        Route::get('flutterwave/ipn','flutterwave_ipn_for_wallet')->name('flutterwave.ipn.wallet');
        Route::get('midtrans-ipn','midtrans_ipn_for_wallet')->name('midtrans.ipn.wallet');
        Route::get('payfast-ipn','payfast_ipn_for_wallet')->name('payfast.ipn.wallet');
        Route::get('cashfree-ipn','cashfree_ipn_for_wallet')->name('cashfree.ipn.wallet');
        Route::get('instamojo-ipn','instamojo_ipn_for_wallet')->name('instamojo.ipn.wallet');
        Route::get('marcadopago-ipn','marcadopago_ipn_for_wallet')->name('marcadopago.ipn.wallet');
        Route::get('squareup-ipn','squareup_ipn_for_wallet' )->name('squareup.ipn.wallet');
        Route::post('cinetpay-ipn', 'cinetpay_ipn_for_wallet' )->name('cinetpay.ipn.wallet');
        Route::post('paytabs-ipn','paytabs_ipn_for_wallet' )->name('paytabs.ipn.wallet');
        Route::post('billplz-ipn','billplz_ipn_for_wallet' )->name('billplz.ipn.wallet');
        Route::post('zitopay-ipn','zitopay_ipn_for_wallet' )->name('zitopay.ipn.wallet');
        Route::post('toyyibpay-ipn','toyyibpay_ipn_for_wallet' )->name('toyyibpay.ipn.wallet');
        Route::get('authorize-ipn','authorizenet_ipn_for_wallet' )->name('authorize.ipn.wallet');
        Route::post('pagali-ipn','pagali_ipn_for_wallet' )->name('pagali.ipn.wallet');
        Route::post('siteways-ipn','siteways_ipn_for_wallet' )->name('siteways.ipn.wallet');
        Route::post('iyzipay-ipn','iyzipay_ipn_for_wallet' )->name('iyzipay.ipn.wallet');
        Route::post('kineticpay-ipn','kineticpay_ipn_for_wallet' )->name('kineticpay.ipn.wallet');
        Route::post('awdpay-ipn','awdpay_ipn_for_wallet' )->name('awdpay.ipn.wallet');
    });
});
//client end

// freelancer

Route::group(['prefix'=>'freelancer/wallet','as'=>'freelancer.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {

    Route::controller(\Modules\Wallet\Http\Controllers\Freelancer\WalletController::class)->group(function () {
        Route::get('history','wallet_history')->name('wallet.history');
        Route::get('paginate/data', 'pagination')->name('wallet.paginate.data');
        Route::get('search-history', 'search_history')->name('wallet.search');
        Route::post('deposit', 'deposit')->name('wallet.deposit');
        Route::get('deposit-cancel-static','deposit_payment_cancel_static')->name('wallet.deposit.payment.cancel.static');
    });

    Route::controller(\Modules\Wallet\Http\Controllers\Freelancer\FreelancerWalletDepositController::class)->group(function () {
        Route::get('paypal-ipn','paypal_ipn_for_wallet')->name('paypal.ipn.wallet');
        Route::post('paytm-ipn','paytm_ipn_for_wallet')->name('paytm.ipn.wallet');
//        Route::get('paystack-ipn','paystack_ipn_for_wallet')->name('paystack.ipn.wallet');
        Route::get('mollie/ipn','mollie_ipn_for_wallet')->name('mollie.ipn.wallet');
        Route::get('stripe/ipn','stripe_ipn_for_wallet')->name('stripe.ipn.wallet');
        Route::post('razorpay-ipn','razorpay_ipn_for_wallet')->name('razorpay.ipn.wallet');
        Route::get('flutterwave/ipn','flutterwave_ipn_for_wallet')->name('flutterwave.ipn.wallet');
        Route::get('midtrans-ipn','midtrans_ipn_for_wallet')->name('midtrans.ipn.wallet');
        Route::get('payfast-ipn','payfast_ipn_for_wallet')->name('payfast.ipn.wallet');
        Route::post('cashfree-ipn','cashfree_ipn_for_wallet')->name('cashfree.ipn.wallet');
        Route::get('instamojo-ipn','instamojo_ipn_for_wallet')->name('instamojo.ipn.wallet');
        Route::get('marcadopago-ipn','marcadopago_ipn_for_wallet')->name('marcadopago.ipn.wallet');
        Route::get('squareup-ipn','squareup_ipn_for_wallet' )->name('squareup.ipn.wallet');
        Route::post('cinetpay-ipn', 'cinetpay_ipn_for_wallet' )->name('cinetpay.ipn.wallet');
        Route::post('paytabs-ipn','paytabs_ipn_for_wallet' )->name('paytabs.ipn.wallet');
        Route::post('billplz-ipn','billplz_ipn_for_wallet' )->name('billplz.ipn.wallet');
        Route::post('zitopay-ipn','zitopay_ipn_for_wallet' )->name('zitopay.ipn.wallet');
        Route::post('toyyibpay-ipn','toyyibpay_ipn_for_wallet' )->name('toyyibpay.ipn.wallet');
        Route::get('authorize-ipn','authorizenet_ipn_for_wallet' )->name('authorize.ipn.wallet');
        Route::post('pagali-ipn','pagali_ipn_for_wallet' )->name('pagali.ipn.wallet');
        Route::post('siteways-ipn','siteways_ipn_for_wallet' )->name('siteways.ipn.wallet');
        Route::post('iyzipay-ipn','iyzipay_ipn_for_wallet' )->name('iyzipay.ipn.wallet');
        Route::post('kineticpay-ipn','kineticpay_ipn_for_wallet' )->name('kineticpay.ipn.wallet');
        Route::post('awdpay-ipn','awdpay_ipn_for_wallet' )->name('awdpay.ipn.wallet');
    });

    Route::controller(\Modules\Wallet\Http\Controllers\Freelancer\WithdrawController::class)->group(function () {
        Route::post('withdraw/request','withdraw_request')->name('wallet.withdraw.request');
        Route::get('withdraw/history', 'withdraw_history')->name('wallet.withdraw.history');
        Route::get('withdraw/paginate/data', 'pagination')->name('wallet.withdraw.paginate.data');
    });

});


// admin
Route::group(['as'=>'admin.','prefix'=>'admin','middleware' => ['auth:admin','setlang']],function() {

    Route::controller(\Modules\Wallet\Http\Controllers\Admin\WalletController::class)->group(function () {
        Route::match(['get','post'],'wallet/deposit-settings', 'deposit_settings')->name('wallet.deposit.settings')->permission('deposit-settings-view');
        Route::get('wallet/history','wallet_history')->name('wallet.history')->permission('deposit-list');
        Route::get('wallet/details/{id}','history_details')->name('wallet.history.details')->permission('deposit-history-details');
        Route::post('wallet/change-status/{id}','change_status')->name('wallet.history.status')->permission('complete-manual-deposit-status');
        Route::get('wallet/paginate/data', 'pagination')->name('wallet.paginate.data');
        Route::get('wallet/search-history', 'search_history')->name('wallet.search');
    });

    Route::controller(\Modules\Wallet\Http\Controllers\Admin\WithdrawGatewayController::class)->group(function () {
        Route::get('withdraw/gateway/settings','gateway_settings')->name('wallet.withdraw.gateway')->permission('withdraw-payment-gateway-list');
        Route::match(['get','post'],'withdraw/withdraw-settings', 'withdraw_settings')->name('wallet.withdraw.settings')->permission('withdraw-settings-view');
        Route::post('withdraw/gateway/create','gateway_create')->name('wallet.withdraw.gateway.create')->permission('withdraw-payment-gateway-add');
        Route::post('withdraw/gateway/update/{id?}','gateway_update')->name('wallet.withdraw.gateway.update')->permission('withdraw-payment-gateway-edit');
        Route::post('withdraw/change-status/{id}','change_status')->name('wallet.withdraw.gateway.status')->permission('withdraw-payment-status-change');
        Route::post('withdraw/delete-gateway/{id}', 'delete_gateway')->name('wallet.withdraw.gateway.delete')->permission('withdraw-payment-gateway-delete');

        Route::get('withdraw/request/all','withdraw_request')->name('wallet.withdraw.request')->permission('withdraw-list');
        Route::post('withdraw/request/update','withdraw_request_update')->name('wallet.withdraw.request.update')->permission('withdraw-status-change ');
        Route::get('withdraw/request/paginate/data', 'pagination')->name('wallet.withdraw.paginate.data');

        //client withdraws
        Route::match(['get','post'],'withdraw/client/enable-disable','enable_disable')->name('client.withdraw.enable.disable.settings')->permission('withdraw-settings-view');
//            Route::get('withdraw/request/all','withdraw_request')->name('wallet.withdraw.request')->permission('withdraw-list');
//            Route::post('withdraw/request/update','withdraw_request_update')->name('wallet.withdraw.request.update')->permission('withdraw-status-change ');
//            Route::get('withdraw/request/paginate/data', 'pagination')->name('wallet.withdraw.paginate.data');
    });

});
