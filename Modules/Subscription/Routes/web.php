<?php

//backend
Route::group(['as'=>'admin.','prefix'=>'admin/subscription','middleware' => ['auth:admin','setlang']],function(){

    // subscription type
    Route::group(['prefix'=>'type'],function(){
        Route::controller(\Modules\Subscription\Http\Controllers\Backend\SubscriptionTypeController::class)->group(function () {
            Route::match(['get','post'],'all-type','all_type')->name('subscription.type.all')->permission('subscription-type-list');
            Route::post('edit-type','edit_type')->name('subscription.type.edit')->permission('subscription-type-edit');
            Route::post('delete/{id}','delete_type')->name('subscription.type.delete')->permission('subscription-type-delete');
            Route::post('bulk-action', 'bulk_action_type')->name('subscription.type.delete.bulk.action')->permission('subscription-type-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('subscription.type.paginate.data');
            Route::get('search-type', 'search_type')->name('subscription.type.search');
        });
    });

    Route::group(['prefix' => 'subs'],function(){
        Route::controller(\Modules\Subscription\Http\Controllers\Backend\SubscriptionController::class)->group(function () {
            Route::get('all-subscription','all_subscription')->name('subscription.all')->permission('subscription-list');
            Route::match(['get','post'],'add-subscription','add_subscription')->name('subscription.add')->permission('subscription-add');
            Route::match(['get','post'],'edit-subscription/{id}','edit_subscription')->name('subscription.edit')->permission('subscription-edit');
            Route::post('delete/{id}','delete_subscription')->name('subscription.delete')->permission('subscription-delete');
            Route::post('status/{id}','status')->name('subscription.status')->permission('subscription-status-change');
            Route::post('bulk-action', 'bulk_action_subscription')->name('subscription.delete.bulk.action')->permission('subscription-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('subscription.paginate.data');
            Route::get('search-type', 'search_subscription')->name('subscription.search');
        });
    });

    Route::group(['prefix' => 'subs/settings'],function(){
        Route::controller(\Modules\Subscription\Http\Controllers\Backend\SubscriptionSettingsController::class)->group(function () {
            Route::match(['get','post'],'limit','limit_settings')->name('subscription.limit.settings')->permission('subscription-connect-settings-view');
            Route::match(['get','post'],'free','free_subscription_settings')->name('free.subscription.settings');
            Route::match(['get','post'],'enable-disable','subscription_enable_disable')->name('subscription.enable.disable.settings');
        });
    });

    Route::group(['prefix' => 'subs/user'],function(){
        Route::controller(\Modules\Subscription\Http\Controllers\Backend\UserSubscriptionController::class)->group(function () {
            Route::get('all','all_subscription')->name('user.subscription.all')->permission('user-subscription-list');
            Route::get('paginate/data', 'pagination')->name('user.subscription.paginate.data');
            Route::get('search-type', 'search_subscription')->name('user.subscription.search');
            Route::post('status/change/{id}', 'change_status')->name('user.subscription.status')->permission('user-subscription-status-change');
            Route::get('get/active/subscription', 'active_subscriptions')->name('user.subscription.active')->permission('user-active-subscription');
            Route::get('get/inactive/subscription', 'inactive_subscriptions')->name('user.subscription.inactive')->permission('user-inactive-subscription');
            Route::get('get/manual/subscription', 'manual_subscriptions')->name('user.subscription.manual')->permission('user-manual-subscription');
            Route::get('notification/read/unread/{id}', 'read_unread')->name('user.subscription.read.unread');
            Route::post('update/manual/payment/status', 'update_manual_payment')->name('user.subscription.update.manual.payment')->permission('user-subscription-manual-payment-status-change');
        });
    });

});

//client subscription
Route::group(['prefix'=>'client/subscription','as'=>'client.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::controller(\Modules\Subscription\Http\Controllers\Client\ClientSubscriptionController::class)->group(function () {
        Route::get('all', 'all_subscription')->name('subscriptions.all');
        Route::get('paginate/data', 'pagination')->name('subscriptions.paginate.data');
        Route::get('search-history', 'search_history')->name('subscriptions.search');
    });
});

//freelancer subscription
Route::group(['prefix'=>'freelancer/subscription','as'=>'freelancer.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::controller(\Modules\Subscription\Http\Controllers\Freelancer\FreelancerSubscriptionController::class)->group(function () {
        Route::get('all', 'all_subscription')->name('subscriptions.all');
        Route::get('paginate/data', 'pagination')->name('subscriptions.paginate.data');
        Route::get('search-history', 'search_history')->name('subscriptions.search');
    });
});


//frontend
Route::group(['middleware' => ['globalVariable', 'maintains_mode','setlang']], function () {
    // subscription
    Route::controller(\Modules\Subscription\Http\Controllers\Frontend\FrontendSubscriptionController::class)->group(function(){
        Route::get('subscriptions/all', 'subscriptions')->name('subscriptions.all');
        Route::get('subscriptions/all/pagination', 'pagination')->name('subscriptions.pagination');
        Route::get('subscriptions/filter/type/wise', 'filter_subscriptions')->name('subscriptions.filter');
        Route::post('subscriptions/user/login', 'user_login')->name('subscriptions.user.login');
        Route::post('subscriptions/buy', 'buy_subscription')->name('subscriptions.buy');
    });

    // buy subscription
    Route::controller(\Modules\Subscription\Http\Controllers\Frontend\BuySubscriptionController::class)->group(function(){
        Route::post('subscriptions/buy', 'buy_subscription')->name('subscriptions.buy');
        Route::get('subscriptions/cancel-static','subscription_payment_cancel_static')->name('subscriptions.buy.payment.cancel.static');
    });

    // ipn routes
    Route::group(['prefix' => 'buy-subscription','as'=>'bs.'],function(){
        Route::controller(\Modules\Subscription\Http\Controllers\Frontend\BuySubscriptionIPNController::class)->group(function () {
            Route::get('paypal/ipn','paypal_ipn_for_subscription')->name('paypal.ipn.subscription');
            Route::post('paytm/ipn','paytm_ipn_for_subscription')->name('paytm.ipn.subscription');
//            Route::get('paystack/ipn','paystack_ipn_for_subscription')->name('paystack.ipn.subscription');
            Route::get('mollie/ipn','mollie_ipn_for_subscription')->name('mollie.ipn.subscription');
            Route::get('stripe/ipn','stripe_ipn_for_subscription')->name('stripe.ipn.subscription');
            Route::post('razorpay/ipn','razorpay_ipn_for_subscription')->name('razorpay.ipn.subscription');
            Route::get('flutterwave/ipn','flutterwave_ipn_for_subscription')->name('flutterwave.ipn.subscription');
            Route::get('midtrans/ipn','midtrans_ipn_for_subscription')->name('midtrans.ipn.subscription');
            Route::get('payfast/ipn','payfast_ipn_for_subscription')->name('payfast.ipn.subscription');
            Route::post('cashfree/ipn','cashfree_ipn_for_subscription')->name('cashfree.ipn.subscription');
            Route::get('instamojo/ipn','instamojo_ipn_for_subscription')->name('instamojo.ipn.subscription');
            Route::get('marcadopago/ipn','marcadopago_ipn_for_subscription')->name('marcadopago.ipn.subscription');
            Route::get('squareup/ipn','squareup_ipn_for_subscription' )->name('squareup.ipn.subscription');
            Route::post('cinetpay/ipn', 'cinetpay_ipn_for_subscription' )->name('cinetpay.ipn.subscription');
            Route::post('paytabs/ipn','paytabs_ipn_for_subscription' )->name('paytabs.ipn.subscription');
            Route::post('billplz/ipn','billplz_ipn_for_subscription' )->name('billplz.ipn.subscription');
            Route::post('zitopay/ipn','zitopay_ipn_for_subscription' )->name('zitopay.ipn.subscription');
            Route::post('toyyibpay/ipn','toyyibpay_ipn_for_subscription' )->name('toyyibpay.ipn.subscription');
            Route::get('authorize/ipn','authorizenet_ipn_for_subscription' )->name('authorize.ipn.subscription');
            Route::post('pagali/ipn','pagali_ipn_for_subscription' )->name('pagali.ipn.subscription');
            Route::post('siteways/ipn','siteways_ipn_for_subscription' )->name('siteways.ipn.subscription');
            Route::post('iyzipay/ipn','iyzipay_ipn_for_subscription' )->name('iyzipay.ipn.subscription');
            Route::post('kineticpay/ipn','kineticpay_ipn_for_subscription' )->name('kineticpay.ipn.subscription');
            Route::post('awdpay/ipn','awdpay_ipn_for_subscription' )->name('awdpay.ipn.subscription');
        });
    });
});

