<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginRegisterController extends Controller
{
    public function login_page_settings(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'login_page_title' => 'nullable|string|max:100',
                'login_page_button_title' => 'nullable|string|max:100',
                'login_page_sidebar_title' => 'nullable|string|max:100',
                'login_page_sidebar_description' => 'nullable|string|max:300',
                'login_page_social_login_enable_disable' => 'nullable',
                'login_page_sidebar_image' => 'nullable',
            ]);

            $all_fields = [
                'login_page_title',
                'login_page_button_title',
                'login_page_sidebar_title',
                'login_page_sidebar_description',
                'login_page_social_login_enable_disable',
                'login_page_sidebar_image',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Login Page Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.login-register-settings.login-settings');
    }

    public function register_page_settings(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'register_page_title' => 'nullable|string|max:100',
                'register_page_button_title' => 'nullable|string|max:100',
                'register_page_sidebar_title' => 'nullable|string|max:100',
                'register_page_sidebar_description' => 'nullable|string|max:300',
                'register_page_social_login_enable_disable' => 'nullable',
                'register_page_sidebar_image' => 'nullable',
                'register_page_choose_role_title' => 'nullable|string|max:100',
                'register_page_choose_role_subtitle' => 'nullable|string|max:100',
                'register_page_choose_join_freelancer_title' => 'nullable|string|max:100',
                'register_page_choose_join_client_title' => 'nullable|string|max:100',
                'register_page_continue_button_title' => 'nullable|string|max:100',
                'toc_page_link' => 'nullable|string|max:200',
                'privacy_policy_link' => 'nullable|string|max:200',
            ]);

            $all_fields = [
                'register_page_title',
                'register_page_button_title',
                'register_page_sidebar_title',
                'register_page_sidebar_description',
                'register_page_social_login_enable_disable',
                'register_page_sidebar_image',
                'register_page_choose_role_title',
                'register_page_choose_role_subtitle',
                'register_page_choose_join_freelancer_title',
                'register_page_choose_join_client_title',
                'register_page_continue_button_title',
                'toc_page_link',
                'privacy_policy_link',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Register Page Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.login-register-settings.register-settings');
    }

    public function register_page_recaptcha_settings(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'recaptcha_site_key' => 'required|string|max:255',
                'recaptcha_secret_key' => 'required|string|max:255',
            ]);

            $all_fields = [
                'recaptcha_site_key',
                'recaptcha_secret_key',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Register Page Recaptcha Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.login-register-settings.register-recaptcha-settings');
    }
}
