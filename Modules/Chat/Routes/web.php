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

use Modules\Chat\Http\Controllers\Admin\PusherSettingsController;
use Modules\Chat\Http\Controllers\ChatController;
use Modules\Chat\Http\Controllers\ClientOfferController;
use Modules\Chat\Http\Controllers\FreelancerChatController;
use Modules\Chat\Http\Controllers\FreelancerOfferController;
use Modules\Chat\Http\Controllers\VendorChatController;

//clients routes
Route::group(['prefix'=>'client/live','as'=>'client.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::get('/chat', [ChatController::class, 'live_chat'])->name('live.chat');
    Route::post("/fetch-chat-freelancer-record", [ChatController::class,'fetch_chat_record'])->name("fetch.chat.freelancer.record");
    Route::post('/message-send', [ChatController::class,'message_send'])->name("message.send");

    Route::get('/all-offers', [ClientOfferController::class,'all_offers'])->name("offers");
    Route::get('/offer-details/{id}', [ClientOfferController::class,'offer_details'])->name("offer.details");
    Route::get('/paginate', [ClientOfferController::class,'pagination'])->name("offer.paginate");
});


//freelancer routes
Route::group(['prefix'=>'freelancer/live','as'=>'freelancer.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::get('/chat', [FreelancerChatController::class, 'live_chat'])->name('live.chat');
    Route::post("fetch-chat-client-record", [FreelancerChatController::class,'fetch_chat_record'])->name("fetch.chat.client.record");
    Route::post('/message-send', [FreelancerChatController::class,'message_send'])->name("message.send");
    Route::post('/offer-send', [FreelancerOfferController::class,'offer_send'])->name("offer.send");

    Route::get('/all-offers', [FreelancerOfferController::class,'all_offers'])->name("offers");
    Route::get('/offer-details/{id}', [FreelancerOfferController::class,'offer_details'])->name("offer.details");
    Route::post('/offer-delete', [FreelancerOfferController::class,'offer_delete'])->name("offer.delete");
    Route::get('/paginate', [FreelancerOfferController::class,'pagination'])->name("offer.paginate");
});


//admin routes
Route::group(['as'=>'admin.','prefix'=>'admin','middleware' => ['auth:admin','setlang']],function(){

        Route::controller(PusherSettingsController::class)->group(function () {
            Route::match(['get','post'],'pusher/settings', 'pusher_settings')->name('pusher.settings');
        });
    });


