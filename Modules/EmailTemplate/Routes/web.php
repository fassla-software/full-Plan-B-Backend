<?php

use Illuminate\Support\Facades\Route;

Route::group(['as'=>'admin.','prefix'=>'admin','middleware' => ['auth:admin','setlang']],function(){
    Route::group(['prefix'=>'email-template'],function(){
        Route::controller(\Modules\EmailTemplate\Http\Controllers\EmailTemplateController::class)->group(function () {
            Route::get('all-templates','all_templates')->name('email.template.all')->permission('email-template-list');
            //user mail
            Route::match(['get','post'],'user-register','user_register')->name('user.register')->permission('email-template-details');
            Route::match(['get','post'],'user-register-welcome','user_register_welcome')->name('user.register.welcome')->permission('email-template-details');
            Route::match(['get','post'],'user-identity-verify-request','user_identity_verify_request')->name('user.identity.verify')->permission('email-template-details');
            Route::match(['get','post'],'user-identity-verify-confirm','user_identity_verify_confirm')->name('user.identity.verify.confirm')->permission('email-template-details');
            Route::match(['get','post'],'user-identity-reverification','user_identity_reverification')->name('user.identity.reverification')->permission('email-template-details');
            Route::match(['get','post'],'user-identity-decline','user_identity_decline')->name('user.identity.decline')->permission('email-template-details');
            Route::match(['get','post'],'user-info-update-email','user_info_update_mail')->name('user.info.update.mail')->permission('email-template-details');
            Route::match(['get','post'],'user-password-change-email','user_password_change_mail')->name('user.password.change.mail')->permission('email-template-details');
            Route::match(['get','post'],'user-status-active-email','user_status_active_mail')->name('user.status.active.mail')->permission('email-template-details');
            Route::match(['get','post'],'user-status-inactive-email','user_status_inactive_mail')->name('user.status.inactive.mail')->permission('email-template-details');
            //2fa mail
            Route::match(['get','post'],'disable-2fa-email','user_2fa_disable_mail')->name('user._2fa.disable.mail')->permission('email-template-details');
            Route::match(['get','post'],'user-verified-email','user_verified_mail')->name('user.verified.mail')->permission('email-template-details');
            //project mail
            Route::match(['get','post'],'project-create-email','project_create_mail')->name('user.project.create')->permission('email-template-details');
            Route::match(['get','post'],'project-activate-email','project_activate_mail')->name('user.project.activate')->permission('email-template-details');
            Route::match(['get','post'],'project-inactivate-email','project_inactivate_mail')->name('user.project.inactivate')->permission('email-template-details');
            Route::match(['get','post'],'project-decline-email','project_decline_mail')->name('user.project.decline')->permission('email-template-details');
            Route::match(['get','post'],'project-edit-email','project_edit_mail')->name('user.project.edit')->permission('email-template-details');
            //deposit mail
            Route::match(['get','post'],'deposit-email','user_deposit_mail')->name('user.wallet.deposit')->permission('email-template-details');
            Route::match(['get','post'],'deposit-email-receive-admin','user_deposit_mail_receive_admin')->name('user.wallet.deposit.receive.mail')->permission('email-template-details');
            //job mail
            Route::match(['get','post'],'job-create-email','job_create_mail')->name('user.job.create')->permission('email-template-details');
            Route::match(['get','post'],'job-activate-email','job_activate_mail')->name('user.job.activate')->permission('email-template-details');
            Route::match(['get','post'],'job-inactivate-email','job_inactivate_mail')->name('user.job.inactivate')->permission('email-template-details');
            Route::match(['get','post'],'job-decline-email','job_decline_mail')->name('user.job.decline')->permission('email-template-details');
            Route::match(['get','post'],'job-edit-email','job_edit_mail')->name('user.job.edit')->permission('email-template-details');
            //subscription mail
            Route::match(['get','post'],'buy-subscription-email','buy_subscription_mail')->name('user.subscription.buy')->permission('email-template-details');
            Route::match(['get','post'],'buy-subscription-email-receive-admin','buy_subscription_mail_receive_admin')->name('user.subscription.buy.receive.mail')->permission('email-template-details');
            Route::match(['get','post'],'subscription-manual-payment-complete-email-to-user','manual_payment_complete_email_to_user')->name('user.subscription.manual.payment.complete.to.user')->permission('email-template-details');
            Route::match(['get','post'],'subscription-manual-payment-complete-email-to-admin','manual_payment_complete_email_to_admin')->name('user.subscription.manual.payment.complete.to.admin')->permission('email-template-details');
            //subscription active inactive mail
            Route::match(['get','post'],'subscription-active','subscription_active')->name('user.subscription.active.email')->permission('email-template-details');
            Route::match(['get','post'],'subscription-inactive','subscription_inactive')->name('user.subscription.inactive.email')->permission('email-template-details');
            //order hold unhold mail
            Route::match(['get','post'],'order-hold','order_hold')->name('order.hold.email');
            Route::match(['get','post'],'order-unhold','order_unhold')->name('order.unhold.email');
            Route::match(['get','post'],'order-manual-payment-complete','manual_order_payment_complete')->name('order.manual.payment.complete.email')->permission('email-template-details');
            //account suspend active mail
            Route::match(['get','post'],'account-suspend','account_suspend')->name('user.account.suspend.email')->permission('email-template-details');
            Route::match(['get','post'],'account-unsuspend','account_unsuspend')->name('user.account.unsuspend.email')->permission('email-template-details');
            //support ticket mail
            Route::match(['get','post'],'support-ticket','support_ticket')->name('support.ticket.email');
            Route::match(['get','post'],'support-ticket-message','support_ticket_message')->name('support.ticket.message.email')->permission('email-template-details');
        });
    });

});

