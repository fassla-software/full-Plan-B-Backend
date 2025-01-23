<?php

use App\Http\Controllers\Frontend\Client\BookmarkController;
use App\Http\Controllers\Frontend\Client\ClientController;
use App\Http\Controllers\Frontend\Client\InvoiceController;
use App\Http\Controllers\Frontend\Client\OrderController;
use App\Http\Controllers\Frontend\Client\JobController;
use App\Http\Controllers\Frontend\Client\DashboardController;
use App\Http\Controllers\Frontend\Google2FA;
use App\Http\Controllers\Frontend\Client\NotificationController;
use App\Http\Controllers\Frontend\Client\ReportController;

// client
Route::group(['prefix'=>'client','as'=>'client.'],function() {

    Route::group(['middleware'=>['auth','Google2FA','globalVariable', 'maintains_mode','setlang']],function(){

        Route::controller(ClientController::class)->group(function () {
            Route::get('profile/logout','logout')->name('logout');
        });
    });

    Route::group(['middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function(){
        Route::controller(ClientController::class)->group(function () {
            Route::get('profile/settings','profile')->name('profile');
            Route::post('profile/edit-profile','edit_profile')->name('profile.edit');
            Route::post('profile/edit-profile-photo','edit_profile_photo')->name('profile.photo.edit');
            Route::match(['get','post'],'profile/identity-verification','identity_verification')->name('identity.verification');
            Route::post('profile/check-password','check_password')->name('password.check');
            Route::match(['get','post'],'profile/change-password','change_password')->name('password');
        });

        //2fa
        Route::controller(Google2FA::class)->group(function () {
            Route::get('profile/-2fa','_2fa_client')->name('_2fa');
            Route::post('profile/-2fa','_2fa_enable_disable_client')->name('_2fa.enable.disable');
            Route::get('profile/-2fa-verify-code','_2fa_verify_code_client')->name('_2fa.verify.code')->withoutMiddleware(['Google2FA']);
            Route::post('profile/-2fa-verify-code','_2fa_verify_secret_code_client')->name('_2fa.verify.secret.code')->withoutMiddleware(['Google2FA']);
        });

        //job
        Route::controller(JobController::class)->group(function () {
            Route::match(['get','post'],'job/create','job_create')->name('job.create');
            Route::match(['get','post'],'job/edit/{id}','job_edit')->name('job.edit');
            Route::get('job/all','all_job')->name('job.all');
            Route::post('job/filter','job_filter')->name('job.filter');
            Route::get('job/details/{id}','job_details')->name('job.details');
            Route::get('job/proposal/details/{id}','proposal_details')->name('job.proposal.details');
            Route::post('job/proposal/add-to-shortlist','add_remove_shortlist')->name('job.proposal.add.to.shortlist');
            Route::post('job/proposal/reject','reject_proposal')->name('job.proposal.reject');
            Route::post('job/open/close','open_close')->name('job.open.close');
            Route::post('job/proposal/filter','job_proposal_filter')->name('job.proposal.filter');
            Route::get('job/paginate/data', 'pagination')->name('job.paginate.data');
            Route::post('job/update/hourly-rate-hours', 'rate_and_hours')->name('job.hourly.rate');
        });

        // orders
        Route::controller(OrderController::class)->group(function () {
            Route::group(['prefix'=>'order'],function(){
                Route::get('all','all_orders')->name('order.all');
                Route::get('paginate/data', 'pagination')->name('order.paginate.data');
                Route::get('sort/by/type', 'sort_by')->name('order.sort.by');
                Route::get('details/{id}','order_details')->name('order.details');
                Route::post('details/milestone-active/{id}','active_milestone')->name('order.milestone.active');
                Route::post('details/report/to/freelancer','report')->name('order.report');
                Route::post('request-revision/for/order/or/milestone','request_revision')->name('order.revision');
                Route::post('approve/milestone/{id}/{type}','order_milestone_approve')->name('order.milestone.approve');
                Route::match(['get','post'],'rating/{id}/','order_rating')->name('order.rating');
            });
        });

        // order invoice
        Route::controller(InvoiceController::class)->group(function () {
            Route::group(['prefix'=>'order/invoice'],function(){
                Route::get('generate/{id}','generate_invoice')->name('order.invoice.generate');
            });
        });

        // notifications
        Route::controller(NotificationController::class)->group(function () {
            Route::group(['prefix'=>'notification'],function(){
                Route::post('read','read_notification')->name('notification.read');
            });
        });

        // reports
        Route::controller(ReportController::class)->group(function () {
            Route::group(['prefix'=>'reports'],function(){
                Route::get('all','all')->name('reports.all');
            });
        });

        // bookmark
        Route::controller(BookmarkController::class)->group(function () {
            Route::group(['prefix'=>'bookmark'],function(){
                Route::post('project-job','bookmark')->name('bookmark');
                Route::post('project-job-remove','bookmark_remove')->name('bookmark.remove');

            });
        });

        //dashboard
        Route::controller(DashboardController::class)->group(function () {
            Route::group(['prefix'=>'dashboard'],function(){
                Route::get('info','dashboard')->name('dashboard');
                Route::post('switch-profile', 'switch_profile')->name('switch.profile');
            });
        });

    });

});
