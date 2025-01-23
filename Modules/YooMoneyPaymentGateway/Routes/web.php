<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\YooMoneyPaymentGateway\App\Http\Controllers\YoomoneyIpnController;

Route::post('frontend/payments/yoomoney-ipn/success/payment',[YoomoneyIpnController::class,'yoomoney_ipn_for_all'])->name('yoomoney.ipn.all');

