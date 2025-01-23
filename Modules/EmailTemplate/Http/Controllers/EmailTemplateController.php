<?php

namespace Modules\EmailTemplate\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailTemplateController extends Controller
{
    public function all_templates()
    {
        return view('emailtemplate::template.all-templates');
    }

    // user identity verify request mail
    public function user_identity_verify_request(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_verify_subject'=>'required|min:5|max:100',
                'user_identity_verify_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_identity_verify_subject',
                'user_identity_verify_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-verify-request');
    }

    // user register mail
    public function user_register(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_register_subject'=>'required|min:5|max:100',
                'user_register_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_register_subject',
                'user_register_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::register.user-register');
    }

    public function user_register_welcome(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_register_welcome_subject'=>'required|min:5|max:100',
                'user_register_welcome_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_register_welcome_subject',
                'user_register_welcome_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::register.user-register-welcome');
    }

    // user identity verify confirm mail
    public function user_identity_verify_confirm(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_verify_confirm_subject'=>'required|min:5|max:100',
                'user_identity_verify_confirm_message'=>'required|min:10|max:5000',
            ]);
            $fields = [
                'user_identity_verify_confirm_subject',
                'user_identity_verify_confirm_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-verify-confirm');
    }

    // user identity reverification mail
    public function user_identity_reverification(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_re_verify_subject'=>'required|min:5|max:100',
                'user_identity_re_verify_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_identity_re_verify_subject',
                'user_identity_re_verify_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-reverification');
    }

   // user identity decline mail
    public function user_identity_decline(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_identity_decline_subject'=>'required|min:5|max:100',
                'user_identity_decline_message'=>'required|min:10|max:3000',
            ]);
            $fields = [
                'user_identity_decline_subject',
                'user_identity_decline_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::identity.user-identity-decline');
    }

   // user info and password update email
    public function user_info_update_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_info_update_subject'=>'required|min:5|max:100',
                'user_info_update_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_info_update_subject',
                'user_info_update_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-info-update.user-info-update-mail');
    }

    // user info update email
    public function user_password_change_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_password_change_subject'=>'required|min:5|max:100',
                'user_password_change_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_password_change_subject',
                'user_password_change_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::password.password-change');
    }

    // user status active email
    public function user_status_active_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_status_active_subject'=>'required|min:5|max:100',
                'user_status_active_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_status_active_subject',
                'user_status_active_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-status.active');
    }

    // user status active email
    public function user_status_inactive_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_status_inactive_subject'=>'required|min:5|max:100',
                'user_status_inactive_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_status_inactive_subject',
                'user_status_inactive_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::user-status.inactive');
    }

    // user status active email
    public function user_2fa_disable_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                '_2fa_disable_subject'=>'required|min:5|max:100',
                '_2fa_disable_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                '_2fa_disable_subject',
                '_2fa_disable_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::-2fa.disable-2fa');
    }
    //user email verified
    public function user_verified_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_email_verified_subject'=>'required|min:5|max:100',
                'user_email_verified_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_email_verified_subject',
                'user_email_verified_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::email-verify.email-verify');
    }

    //project
    //user project create mail
    public function project_create_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_create_email_subject'=>'required|min:5|max:100',
                'project_create_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'project_create_email_subject',
                'project_create_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-create');
    }

    //user project edit mail
    public function project_edit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_edit_email_subject'=>'required|min:5|max:100',
                'project_edit_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'project_edit_email_subject',
                'project_edit_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-edit');
    }

    //user project activate mail
    public function project_activate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_approve_email_subject'=>'required|min:5|max:100',
                'project_approve_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'project_approve_email_subject',
                'project_approve_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-activate');
    }

    //user project activate mail
    public function project_inactivate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_inactivate_email_subject'=>'required|min:5|max:100',
                'project_inactivate_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'project_inactivate_email_subject',
                'project_inactivate_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-inactivate');
    }

    //user project decline mail
    public function project_decline_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'project_decline_email_subject'=>'required|min:5|max:100',
                'project_decline_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'project_decline_email_subject',
                'project_decline_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::project.project-decline');
    }

    //deposit
    //user deposit mail
    public function user_deposit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_deposit_to_wallet_subject'=>'required|min:5|max:100',
                'user_deposit_to_wallet_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_deposit_to_wallet_subject',
                'user_deposit_to_wallet_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::wallet.user-deposit');
    }
    //user deposit mail to admin
    public function user_deposit_mail_receive_admin(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_deposit_to_wallet_subject_admin'=>'required|min:5|max:100',
                'user_deposit_to_wallet_message_admin'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_deposit_to_wallet_subject_admin',
                'user_deposit_to_wallet_message_admin',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::wallet.user-deposit-admin-mail');
    }

    //job
    //user job create mail
    public function job_create_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_create_email_subject'=>'required|min:5|max:100',
                'job_create_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'job_create_email_subject',
                'job_create_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-create');
    }

    //user job edit mail
    public function job_edit_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_edit_email_subject'=>'required|min:5|max:100',
                'job_edit_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'job_edit_email_subject',
                'job_edit_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-edit');
    }

    //user job activate mail
    public function job_activate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_approve_email_subject'=>'required|min:5|max:100',
                'job_approve_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'job_approve_email_subject',
                'job_approve_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-activate');
    }

    //user job activate mail
    public function job_inactivate_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_inactivate_email_subject'=>'required|min:5|max:100',
                'job_inactivate_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'job_inactivate_email_subject',
                'job_inactivate_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-inactivate');
    }

   //user job decline mail
    public function job_decline_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'job_decline_email_subject'=>'required|min:5|max:100',
                'job_decline_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'job_decline_email_subject',
                'job_decline_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::job.job-decline');
    }

    //user subscription buy mail to user
    public function buy_subscription_mail(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_subscription_purchase_subject'=>'required|min:5|max:100',
                'user_subscription_purchase_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_subscription_purchase_subject',
                'user_subscription_purchase_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::buy-subscription.user-subscription-buy');
    }

    //user subscription buy mail to admin
    public function buy_subscription_mail_receive_admin(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'user_subscription_purchase_admin_email_subject'=>'required|min:5|max:100',
                'user_subscription_purchase_admin_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'user_subscription_purchase_admin_email_subject',
                'user_subscription_purchase_admin_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::buy-subscription.user-subscription-buy-admin-mail');
    }

    //user subscription manual payment complete mail to user
    public function manual_payment_complete_email_to_user(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'manual_subscription_complete_subject'=>'required|min:5|max:100',
                'manual_subscription_complete_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'manual_subscription_complete_subject',
                'manual_subscription_complete_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::subscription-manual-payment.payment-complete');
    }

    //user subscription manual payment complete mail to admin
    public function manual_payment_complete_email_to_admin(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'manual_subscription_complete_subject_to_admin'=>'required|min:5|max:100',
                'manual_subscription_complete_message_to_admin'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'manual_subscription_complete_subject_to_admin',
                'manual_subscription_complete_message_to_admin',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::subscription-manual-payment.payment-complete-to-admin');
    }

    //subscription active mail to user
    public function subscription_active(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'subscription_active_subject'=>'required|min:5|max:100',
                'subscription_active_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'subscription_active_subject',
                'subscription_active_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::subscription-active-inactive.active');
    }

    //subscription inactive mail to user
    public function subscription_inactive(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'subscription_inactive_subject'=>'required|min:5|max:100',
                'subscription_inactive_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'subscription_inactive_subject',
                'subscription_inactive_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::subscription-active-inactive.inactive');
    }

    //order hold mail
    public function order_hold(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'order_hold_subject'=>'required|min:5|max:100',
                'order_hold_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'order_hold_subject',
                'order_hold_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::order.hold');
    }

    //order unhold mail
    public function order_unhold(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'order_unhold_subject'=>'required|min:5|max:100',
                'order_unhold_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'order_unhold_subject',
                'order_unhold_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::order.unhold');
    }

    //order manual payment complete
    public function manual_order_payment_complete(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'order_manual_payment_complete_subject'=>'required|min:5|max:100',
                'order_manual_payment_complete_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'order_manual_payment_complete_subject',
                'order_manual_payment_complete_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::order.payment-success');
    }

    //account suspend mail
    public function account_suspend(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'account_suspend_subject'=>'required|min:5|max:100',
                'account_suspend_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'account_suspend_subject',
                'account_suspend_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::account.suspend');
    }

    //account unsuspend mail
    public function account_unsuspend(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'account_unsuspend_subject'=>'required|min:5|max:100',
                'account_unsuspend_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'account_unsuspend_subject',
                'account_unsuspend_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::account.unsuspend');
    }

    //support ticket
    public function support_ticket(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'support_ticket_subject'=>'required|min:5|max:100',
                'support_ticket_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'support_ticket_subject',
                'support_ticket_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::ticket.support-ticket');
    }


    //support ticket message email
    public function support_ticket_message(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'support_ticket_message_email_subject'=>'required|min:5|max:100',
                'support_ticket_message_email_message'=>'required|min:10|max:100000',
            ]);
            $fields = [
                'support_ticket_message_email_subject',
                'support_ticket_message_email_message',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('emailtemplate::ticket.support-ticket-message');
    }



}
