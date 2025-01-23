<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin','setlang']], function () {
    Route::group(['prefix' => 'payment-settings'], function () {
        Route::controller(\Modules\PaymentGatewaySettings\Http\Controllers\PaymentGatewaySettingsController::class)->group(function () {
            Route::match(['get', 'post'], 'payment-info', 'payment_info')->name('payment.settings.info')->permission('payment-info-settings');
            Route::match(['get', 'post'], 'payment-gateway', 'payment_gateway')->name('payment.settings.gateway')->permission('payment-gateway-settings');
        });
    });

});

