<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\CategoryJobController;
use App\Http\Controllers\Frontend\CategoryProjectController;
use App\Http\Controllers\Frontend\FormController;
use App\Http\Controllers\Frontend\Freelancer\HeavyEquipmentController;
use App\Http\Controllers\Frontend\Freelancer\NewCategoryController;
use App\Http\Controllers\Frontend\FreelancerListController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\FrontendHomeController;
use App\Http\Controllers\Frontend\FrontendJobsController;
use App\Http\Controllers\Frontend\FrontendProjectsController;
use App\Http\Controllers\Frontend\FrontendSubscriptionController;
use App\Http\Controllers\Frontend\JobDetailsController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\OrderIPNController;
use App\Http\Controllers\Frontend\ProfileDetailsController;
use App\Http\Controllers\Frontend\ProjectDetailsController;
use App\Http\Controllers\Frontend\SkillJobController;
use App\Http\Controllers\Frontend\SocialLoginController;
use App\Http\Controllers\Frontend\SubcategoryJobController;
use App\Http\Controllers\Frontend\SubcategoryProjectController;
use App\Models\JobPost;
use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/client.php';
require_once __DIR__ . '/freelancer.php';
require_once __DIR__ . '/admin.php';

// new routes
Route::controller(NewCategoryController::class)->group(function () {
    Route::get('/form/{subCategory}', 'showForm')->name('form.show');
    Route::post('/form/{subCategory}/store', 'storeData')->name('form.store');
});


// frontend starts
Route::group(['middleware' => ['globalVariable', 'maintains_mode','setlang']], function () {

    // public routes for user and admin
    Route::controller(AdminUserController::class)->group(function(){
        Route::post('get-state','get_country_state')->name('au.state.all');
        Route::post('get-city','get_state_city')->name('au.city.all');
        Route::post('get-subcategory','get_subcategory')->name('au.subcategory.all');
    });

    // user registration
    Route::controller(RegisterController::class)->group(function(){
        Route::post('user-name-availability','userNameAvailability')->name('user.name.availability');
        Route::post('email-availability','emailAvailability')->name('user.email.availability');
        Route::post('phone-number-availability','phoneNumberAvailability')->name('user.phone.number.availability');
        Route::match(['get','post'],'user-register','userRegister')->name('user.register');
        Route::match(['get', 'post'], 'email-verify', 'emailVerify')->name('email.verify')->middleware('auth:web');
        Route::get('resend-verify-code-again', 'resendCode')->name('resend.verify.code')->middleware('auth:web');
    });

    // user login
    Route::controller(LoginController::class)->group(function(){
        Route::match(['get', 'post'], 'login', 'userLogin')->name('user.login');
        Route::match(['get', 'post'], 'forget-password', 'forgetPassword')->name('user.forgot.password');
        Route::match(['get', 'post'], 'password-reset-otp', 'passwordResetOtp')->name('user.forgot.password.otp');
        Route::match(['get', 'post'], 'password-reset', 'passwordReset')->name('user.forgot.password.reset');
    });

    // user social login
    Route::controller(SocialLoginController::class)->group(function(){
        Route::get('facebook/callback', 'facebook_callback')->name('facebook.callback');
        Route::get('facebook/redirect', 'facebook_redirect')->name('login.facebook.redirect');
        Route::get('google/callback', 'google_callback')->name('google.callback');
        Route::get('google/redirect', 'google_redirect')->name('login.google.redirect');
    });

    // freelancer public profile view
    Route::controller(ProfileDetailsController::class)->group(function(){
        Route::get('freelancer/profile-details/{username}', 'profile_details')->name('freelancer.profile.details');
        Route::post('freelancer/portfolio-details/display', 'portfolio_details')->name('freelancer.portfolio.details');
    });

    //freelancer list
    Route::controller(FreelancerListController::class)->group(function(){
        Route::get('talents/all', 'talents')->name('talents.all');
        Route::get('talents/all/filter', 'talents_filter')->name('talents.filter');
        Route::get('talents/all/pagination', 'pagination')->name('talents.pagination');
        Route::get('talents/filter/reset', 'reset')->name('talents.filter.reset');
    });

    Route::group(['middleware'=>'preventprojecturl'],function(){
        // all projects
        Route::controller(FrontendProjectsController::class)->group(function(){
            Route::get('projects/all', 'projects')->name('projects.all');
            Route::get('projects/all/pro', 'pro_projects')->name('pro.projects.all');
            Route::get('projects/all/filter', 'projects_filter')->name('projects.filter');
            Route::get('projects/all/pagination', 'pagination')->name('projects.pagination');
            Route::get('projects/filter/reset', 'reset')->name('projects.filter.reset');
        });
        // category projects
        Route::controller(CategoryProjectController::class)->group(function(){
            Route::get('categories/{slug}', 'category_projects')->name('category.projects');
            Route::get('categories/projects/filter', 'category_project_filter')->name('category.projects.filter');
            Route::get('categories/project/pagination', 'pagination')->name('category.project.pagination');
            Route::get('categories/project/filter/reset', 'reset')->name('category.project.filter.reset');
        });
        // subcategory projects
        Route::controller(SubcategoryProjectController::class)->group(function(){
            Route::get('sub-categories/{slug}', 'sub_category_projects')->name('subcategory.projects');
            Route::get('sub-categories/projects/filter', 'sub_category_project_filter')->name('subcategory.projects.filter');
            Route::get('sub-categories/project/pagination', 'pagination')->name('subcategory.project.pagination');
            Route::get('sub-categories/project/filter/reset', 'reset')->name('subcategory.project.filter.reset');
        });
    });

    Route::group(['middleware'=>'preventjoburl'],function(){
        // jobs
        Route::controller(FrontendJobsController::class)->group(function(){
            Route::get('jobs/all', 'jobs')->name('jobs.all');
            Route::get('jobs/all/filter', 'jobs_filter')->name('jobs.filter');
            Route::get('jobs/all/pagination', 'pagination')->name('jobs.pagination');
            Route::get('jobs/filter/reset', 'reset')->name('jobs.filter.reset');
        });
        // category jobs
        Route::controller(CategoryJobController::class)->group(function(){
            Route::get('jobs/categories/pagination', 'pagination')->name('category.jobs.pagination');
            Route::get('jobs/categories/filter', 'category_jobs_filter')->name('category.jobs.filter');
            Route::get('jobs/categories/filter/reset', 'reset')->name('category.jobs.filter.reset');
            Route::get('jobs/categories/{slug}', 'category_jobs')->name('category.jobs');
        });
        // subcategory jobs
        Route::controller(SubcategoryJobController::class)->group(function(){
            Route::get('jobs/subcategories/pagination', 'pagination')->name('subcategory.jobs.pagination');
            Route::get('jobs/subcategories/filter', 'subcategory_jobs_filter')->name('subcategory.jobs.filter');
            Route::get('jobs/subcategories/filter/reset', 'reset')->name('subcategory.jobs.filter.reset');
            Route::get('jobs/subcategories/{slug}', 'subcategory_jobs')->name('subcategory.jobs');
        });
        // skill jobs
        Route::controller(SkillJobController::class)->group(function(){
            Route::get('jobs/skill/pagination', 'pagination')->name('skill.jobs.pagination');
            Route::get('jobs/skill/filter', 'skill_jobs_filter')->name('skill.jobs.filter');
            Route::get('jobs/skill/filter/reset', 'reset')->name('skill.jobs.filter.reset');
            Route::get('jobs/skill/{name?}', 'skill_jobs')->name('skill.jobs');
        });
    });

    // home job search
    Route::controller(FrontendHomeController::class)->group(function(){
        Route::get('job/project/search/from/home/page', 'project_or_job_search')->name('home.job.project.search');
    });

    //orders
    Route::controller(OrderController::class)->group(function(){
        Route::post('login/to/continue/order', 'user_login')->name('order.user.login');
        Route::post('order/user/confirm', 'user_order_confirm')->name('order.user.confirm');
        Route::get('order/success/page/{id}', 'user_order_success_page')->name('order.user.success.page');
        Route::get('order/payment/cancel/static', 'order_payment_cancel_static')->name('order.payment.cancel.static');
    });

    //order ipns
    Route::group(['prefix' => 'order','as'=>'pro.'],function(){
        Route::controller(OrderIPNController::class)->group(function () {
            Route::get('paypal-ipn','paypal_ipn_for_order')->name('paypal.ipn.order');
            Route::post('paytm-ipn','paytm_ipn_for_order')->name('paytm.ipn.order');
//            Route::get('paystack-ipn','paystack_ipn_for_order')->name('paystack.ipn.order');
            Route::get('mollie/ipn','mollie_ipn_for_order')->name('mollie.ipn.order');
            Route::get('stripe/ipn','stripe_ipn_for_order')->name('stripe.ipn.order');
            Route::post('razorpay-ipn','razorpay_ipn_for_order')->name('razorpay.ipn.order');
            Route::get('flutterwave/ipn','flutterwave_ipn_for_order')->name('flutterwave.ipn.order');
            Route::get('midtrans-ipn','midtrans_ipn_for_order')->name('midtrans.ipn.order');
            Route::get('payfast-ipn','payfast_ipn_for_order')->name('payfast.ipn.order');
            Route::post('cashfree-ipn','cashfree_ipn_for_order')->name('cashfree.ipn.order');
            Route::get('instamojo-ipn','instamojo_ipn_for_order')->name('instamojo.ipn.order');
            Route::get('marcadopago-ipn','marcadopago_ipn_for_order')->name('marcadopago.ipn.order');
            Route::get('squareup-ipn','squareup_ipn_for_order' )->name('squareup.ipn.order');
            Route::post('cinetpay-ipn', 'cinetpay_ipn_for_order' )->name('cinetpay.ipn.order');
            Route::post('paytabs-ipn','paytabs_ipn_for_order' )->name('paytabs.ipn.order');
            Route::post('billplz-ipn','billplz_ipn_for_order' )->name('billplz.ipn.order');
            Route::post('zitopay-ipn','zitopay_ipn_for_order' )->name('zitopay.ipn.order');
            Route::post('toyyibpay-ipn','toyyibpay_ipn_for_order' )->name('toyyibpay.ipn.order');
            Route::get('authorize-ipn','authorizenet_ipn_for_order' )->name('authorize.ipn.order');
            Route::post('pagali-ipn','pagali_ipn_for_order' )->name('pagali.ipn.order');
            Route::post('siteways-ipn','siteways_ipn_for_order' )->name('siteways.ipn.order');
            Route::post('iyzipay-ipn','iyzipay_ipn_for_order' )->name('iyzipay.ipn.order');
            Route::post('kineticpay-ipn','kineticpay_ipn_for_order' )->name('kineticpay.ipn.order');
            Route::post('awdpay-ipn','awdpay_ipn_for_order' )->name('awdpay.ipn.order');
        });
    });

    // frontend custom form builders
    Route::controller(FormController::class)->group(function(){
        Route::post('form/custom-form/submit', 'custom_form_submit')->name('custom.form.submit');
    });

    Route::group(['middleware'=>'preventjoburl'],function(){
        //job details
        Route::controller(JobDetailsController::class)->group(function(){
            Route::get('jobs/{username}/{slug}', 'job_details')->name('job.details');
            Route::post('jobs/proposal/send/to-client', 'job_proposal_send')->name('job.proposal.send');
        });
    });

    Route::group(['middleware'=>'preventprojecturl'],function(){
        //project details
        Route::controller(ProjectDetailsController::class)->group(function(){
            Route::get('projects/{username}/{slug}', 'project_details')->name('project.details');
            Route::get('/project/review/load/more/data', 'load_more_review')->name('project.review.load.more');
        });
    });

    //dynamic single page
    Route::controller(FrontendController::class)->group(function(){
        Route::get('/','home_page')->name('homepage');
        Route::get('/{slug}', 'dynamic_single_page')->name('frontend.dynamic.page');
    });
});

