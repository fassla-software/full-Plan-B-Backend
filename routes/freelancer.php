<?php

// freelancer
use App\Http\Controllers\Frontend\Freelancer\AccountSetupController;
use App\Http\Controllers\Frontend\Freelancer\BookmarkController;
use App\Http\Controllers\Frontend\Freelancer\DashboardController;
use App\Http\Controllers\Frontend\Freelancer\InvoiceController;
use App\Http\Controllers\Frontend\Freelancer\ProjectController;
use App\Http\Controllers\Frontend\Freelancer\FreelancerController;
use App\Http\Controllers\Frontend\Freelancer\PortfolioController;
use App\Http\Controllers\Frontend\Freelancer\OrderController;
use App\Http\Controllers\Frontend\Freelancer\NotificationController;
use App\Http\Controllers\Frontend\Freelancer\ProposalController;
use App\Http\Controllers\Frontend\Google2FA;


Route::group(['prefix'=>'freelancer','as'=>'freelancer.'],function() {

    Route::group(['middleware'=>['auth','Google2FA','globalVariable', 'maintains_mode','setlang']],function(){
        Route::controller(FreelancerController::class)->group(function () {
            Route::get('profile/logout','logout')->name('logout');
        });
    });

    Route::group(['middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function(){
        // Profile general info
        Route::controller(FreelancerController::class)->group(function () {
            Route::get('profile/settings','profile')->name('profile');
            Route::post('profile/edit-profile','edit_profile')->name('profile.edit');
            Route::post('profile/submit/feedback','submit_feedback')->name('submit.feedback');
            Route::post('profile/edit-profile-photo','edit_profile_photo')->name('profile.photo.edit');
            Route::match(['get','post'],'profile/identity-verification','identity_verification')->name('identity.verification');
            Route::post('profile/check-password','check_password')->name('password.check');
            Route::match(['get','post'],'profile/change-password','change_password')->name('password');
            Route::match(['get','post'],'account/delete','account_delete')->name('account.delete');
        });

        Route::controller(Google2FA::class)->group(function () {
            Route::get('profile/-2fa','_2fa_freelancer')->name('_2fa');
            Route::post('profile/-2fa','_2fa_enable_disable_freelancer')->name('_2fa.enable.disable');
            Route::get('profile/-2fa-verify-code','_2fa_verify_code_freelancer')->name('_2fa.verify.code')->withoutMiddleware(['Google2FA']);
            Route::post('profile/-2fa-verify-code','_2fa_verify_secret_code_freelancer')->name('_2fa.verify.secret.code')->withoutMiddleware(['Google2FA']);
        });

        // Account setup
        Route::controller(AccountSetupController::class)->group(function () {
            Route::get('account/setup','account_setup')->name('account.setup');
            Route::post('account/add-introduction','add_introduction')->name('account.introduction.add');
            Route::post('account/add-experience','add_experience')->name('account.experience.add');
            Route::post('account/update-experience','update_experience')->name('account.experience.update');
            Route::post('account/add-education','add_education')->name('account.education.add');
            Route::post('account/update-education','update_education')->name('account.education.update');
            Route::get('account/search-category', 'search_category')->name('account.category.search');
            Route::post('account/add-work', 'add_work')->name('account.work.add');
            Route::post('account/add-skill', 'add_skill')->name('account.skill.add');
            Route::post('account/add-hourly-rate', 'add_hourly_rate')->name('account.hourly.rate.add');
            Route::post('account/upload-profile-photo','upload_profile_photo')->name('account.profile.photo.upload');
            Route::get('account/congrats', 'congrats')->name('account.congrats');
        });

        // Create project
        Route::controller(ProjectController::class)->group(function () {
            Route::group(['prefix'=>'project'],function(){
                Route::match(['get','post'],'create-project','create_project')->name('project.create');
                Route::match(['get','post'],'edit-project/{id}','edit_project')->name('project.edit');
                Route::post('delete-project','delete_project')->name('project.delete');

                Route::get('project-preview','project_preview')->name('project.preview');
                Route::get('project-description','project_description')->name('project.description');
            });
        });

        // Create portfolio
        Route::controller(PortfolioController::class)->group(function () {
            Route::group(['prefix'=>'portfolio'],function(){
                Route::post('add','add_portfolio')->name('portfolio.add');
                Route::post('edit','edit_portfolio')->name('portfolio.edit');
                Route::post('delete','delete_portfolio')->name('portfolio.delete');
                Route::post('delete-education','delete_education')->name('education.delete');
                Route::post('delete-experience','delete_experience')->name('experience.delete');
                Route::post('availability-status','availability_status')->name('availability.status');
                Route::post('work-availability-status','work_availability_status')->name('work.availability.status');
                Route::post('profile-details-update','profile_details_update')->name('profile.details.update');
                Route::post('profile-details-hourly-rate','profile_details_hourly_rate_update')->name('profile.details.hourly.rate.update');
            });
        });

        // orders
        Route::controller(OrderController::class)->group(function () {
            Route::group(['prefix'=>'order'],function(){
                Route::get('all','all_orders')->name('order.all');
                Route::get('sort/by/type', 'sort_by')->name('order.sort.by');
                Route::get('paginate/data', 'pagination')->name('order.paginate.data');
                Route::get('details/{id}','order_details')->name('order.details');
                Route::post('accept/{id}','order_accept')->name('order.accept');
                Route::post('decline/{id}','order_decline')->name('order.decline');
                Route::post('submit','order_submit')->name('order.submit');
                Route::post('details/report/to/client','report')->name('order.report');
                Route::match(['get','post'],'submit/rating/{id}/','order_rating')->name('order.rating');
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

        // bookmark
        Route::controller(BookmarkController::class)->group(function () {
            Route::group(['prefix'=>'bookmark'],function(){
                Route::post('project-job','bookmark')->name('bookmark');
                Route::post('project-job-remove','bookmark_remove')->name('bookmark.remove');
            });
        });

        //proposals
        Route::controller(ProposalController::class)->group(function () {
            Route::group(['prefix'=>'proposal'],function(){
                Route::get('all','all_proposal')->name('proposal');
                Route::get('paginate/data', 'pagination')->name('proposal.paginate.data');
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
