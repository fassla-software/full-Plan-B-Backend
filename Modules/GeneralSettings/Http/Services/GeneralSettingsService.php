<?php

namespace Modules\GeneralSettings\Http\Services;

use App\Mail\BasicMail;
use App\Models\Admin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Validation\Validator;

class GeneralSettingsService
{
    public function reading($request)
    {
        $request->validate([
            'home_page' => 'nullable|string',
        ]);
        $fields = [
            'home_page',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        toastr_success(__('Reading Settings Successfully Updated.'));
        return back();
    }

    public function navbar_global_variant($request)
    {
        $request->validate([
            'global_navbar_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_navbar_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        toastr_success(__('Navbar Variant Updated Successfully.'));
        return back();
    }

    public function footer_global_variant($request)
    {
        $request->validate([
            'global_footer_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_footer_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        toastr_success(__('Footer Variant Updated Successfully.'));
        return back();
    }

    public function site_identity($request)
    {
        $fields = [
            'site_logo',
            'site_white_logo',
            'site_favicon',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        toastr_success(__('Site Identity Settings Updated successfully.'));
        return back();
    }
    public function basic_settings($request)
    {
        $all_fields = [
            'site_title',
            'site_tag_line',
            'site_footer_copyright',
            'disable_user_email_verify',
            'site_maintenance_mode',
            'admin_loader_animation',
            'site_loader_animation',
            'site_force_ssl_redirection',
            'site_google_captcha_enable',
            'social_login_enable_disable',
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }
        toastr_success(__('Basic Settings Updated Successfully.'));
        return back();
    }

    public function color_settings($request)
    {
        $all_fields = [
            'main_color_one',
            'main_color_two',
            'secondary_color',
            'heading_color',
            'paragraph_color',
            'body_color',
        ];

        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }
        toastr_success(__('Color Settings Updated Successfully.'));
        return back();
    }

    public function typography_settings($request)
    {
        $all_fields = [
            'body_font_family',
            'heading_font_family',
            'section_font_family',
            'extra_body_font',
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        $body_font_variant = !empty($request->body_font_variant) ?  $request->body_font_variant : ['regular'];
        $heading_font_variant = !empty($request->heading_font_variant) ?  $request->heading_font_variant : ['regular'];
        $section_font_variant = !empty($request->section_font_variant) ?  $request->section_font_variant : ['regular'];

        update_static_option('heading_font', $request->heading_font);
        update_static_option('body_font_variant', serialize($body_font_variant));
        update_static_option('heading_font_variant', serialize($heading_font_variant));
        update_static_option('section_font_variant', serialize($section_font_variant));
        toastr_success(__('Typography Settings Updated Successfully.'));
        return back();
    }

    public function seo_settings($request)
    {
        $fields = [
            'site_meta_tags',
            'site_meta_description',
            'og_meta_title',
            'og_meta_description',
            'og_meta_site_name',
            'og_meta_url',
            'og_meta_image'
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        toastr_success(__('Seo Settings Updated Successfully.'));
        return back();
    }

    public function third_party_scripts_settings($request)
    {
        $fields = [
            'site_third_party_tracking_code',
        ];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }
        toastr_success(__('Third Party Scripts Updated Successfully.'));
        return back();
    }

    public function social_login_settings($request)
    {
        $fields = [
            'facebook_client_id',
            'facebook_client_secret',
            'google_client_id',
            'google_client_secret',
        ];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }
        setEnvValue([
            'FACEBOOK_CLIENT_ID' => "'$request->facebook_client_id'",
            'FACEBOOK_CLIENT_SECRET' => "'$request->facebook_client_secret'",
            'FACEBOOK_CALLBACK_URL' => route('facebook.callback'),
            'GOOGLE_CLIENT_ID' => "'$request->google_client_id'",
            'GOOGLE_CLIENT_SECRET' => "'$request->google_client_secret'",
            'GOOGLE_CALLBACK_URL' => route('google.callback'),
        ]);
        toastr_success(__('Social Login Settings Updated Successfully.'));
        return back();
    }

    public function email_template_settings($request)
    {
        $request->validate([
            'site_global_email' => 'required|string',
            'site_global_email_template' => 'required|string',
        ]);

        $save_data = [
            'site_global_email',
            'site_global_email_template'
        ];
        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }
        setEnvValue([
            'MAIL_FROM_ADDRESS' => "'$request->site_global_email'",
        ]);
        toastr_success(__('Email Template Updated Successfully.'));
        return back();
    }

    public function smtp_settings($request)
    {
        $request->validate([
            'site_smtp_mail_host' => 'required|string',
            'site_smtp_mail_port' => 'required|string',
            'site_smtp_mail_username' => 'required|string',
            'site_smtp_mail_password' => 'required|string',
            'site_smtp_mail_encryption' => 'required|string'
        ]);

        $all_fields = [
            'site_smtp_mail_mailer',
            'site_smtp_mail_host',
            'site_smtp_mail_port',
            'site_smtp_mail_username',
            'site_smtp_mail_password',
            'site_smtp_mail_encryption',
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        setEnvValue([
            'MAIL_DRIVER' => $request->site_smtp_mail_mailer,
            'MAIL_HOST' => $request->site_smtp_mail_host,
            'MAIL_PORT' => $request->site_smtp_mail_port,
            'MAIL_USERNAME' => $request->site_smtp_mail_username,
            'MAIL_PASSWORD' => '"'.$request->site_smtp_mail_password.'"',
            'MAIL_ENCRYPTION' => $request->site_smtp_mail_encryption
        ]);
        toastr_success(__('Email Template Updated Successfully.'));
        return back();
    }

    public function test_smtp_settings($request)
    {
        Validator::make($request->all(),[
            'subject' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'message' => 'required|string',
        ])->validatewithbag('EmailSend');

        try {
            Mail::to($request->email)->send(new BasicMail([
                'subject' => $request->subject,
                'message' => $request->message
            ]));
        }catch (\Exception $e){
            return back()->withErrors(['errors'=>$e->getMessage()]);
        }
        toastr_success(__('Email Send Successfully.'));
        return back();
    }

    public function custom_css($request)
    {
        file_put_contents('assets/frontend/css/dynamic-style.css', $request->custom_css_area);
        toastr_success(__('Custom CSS Updated Successfully.'));
        return back();
    }

    public function custom_js($request)
    {
        file_put_contents('assets/frontend/js/dynamic-script.js', $request->custom_js_area);
        toastr_success(__('Custom JS Updated Successfully.'));
        return back();
    }

    public function gdpr_settings($request)
    {
        $request->validate([
            'site_gdpr_cookie_enabled' => 'nullable|string|max:191',
            'site_gdpr_cookie_expire' => 'required|string|max:191',
            'site_gdpr_cookie_delay' => 'required|string|max:191',
            "site_gdpr_cookie_title" => 'nullable|string',
            "site_gdpr_cookie_message" => 'nullable|string',
            "site_gdpr_cookie_more_info_label" => 'nullable|string',
            "site_gdpr_cookie_more_info_link" => 'nullable|string',
            "site_gdpr_cookie_accept_button_label" => 'nullable|string',
            "site_gdpr_cookie_decline_button_label" => 'nullable|string',
        ]);
        $fields = [
            "site_gdpr_cookie_title",
            "site_gdpr_cookie_message",
            "site_gdpr_cookie_more_info_label",
            "site_gdpr_cookie_more_info_link",
            "site_gdpr_cookie_accept_button_label",
            "site_gdpr_cookie_decline_button_label",
            "site_gdpr_cookie_manage_button_label",
            "site_gdpr_cookie_manage_title",
        ];

        foreach ($fields as $field){
            update_static_option($field, $request->$field);
        }

        $all_fields = [
            'site_gdpr_cookie_manage_item_title',
            'site_gdpr_cookie_manage_item_description',
        ];

        foreach ($all_fields as $field){
            $value = $request->$field ?? [];
            update_static_option($field,serialize($value));
        }

        update_static_option('site_gdpr_cookie_delay', $request->site_gdpr_cookie_delay);
        update_static_option('site_gdpr_cookie_enabled', $request->site_gdpr_cookie_enabled);
        update_static_option('site_gdpr_cookie_expire', $request->site_gdpr_cookie_expire);
        toastr_success(__('Custom JS Updated Successfully.'));
        return back();
    }

    public function licence_settings($request)
    {
        $request->validate([
            'item_purchase_key' => 'required|string|max:191'
        ]);
        $response = Http::get('https://bytesed.com/license/new', [
            'purchase_code' => $request->item_purchase_key,
            'site_url' => url('/'),
            'item_unique_key' => 'vwYO8y6j9WeciiEIcEBCUeRMTSP8gEaA',
        ]);
        $result = $response->json();

        if($response->ok() && !is_null($result)){
            update_static_option('item_purchase_key', $request->item_purchase_key);
            update_static_option('item_license_status', $result['license_status']);
            update_static_option('item_license_msg', $result['msg']);

            $type = 'verified' == $result['license_status'] ? 'success' : 'danger';
            setcookie("site_license_check", "", time() - 3600, '/');
            $license_info = [
                "item_license_status" => $result['license_status'],
                "last_check" => time(),
                "purchase_code" => get_static_option('item_purchase_key'),
                "xgenious_app_key" => env('XGENIOUS_APP_KEY'),
                "author" => env('XGENIOUS_APP_AUTHOR'),
                "message" => $result['msg']
            ];
            toastr_success(__('Licence Settings Updated Successfully.'));
            return back();
        }
    }

    public function cache_settings($request)
    {
        $request->validate([
            'cache_type' => 'required|string'
        ]);
        Artisan::call($request->cache_type . ':clear');
        toastr_success(__('Cache Cleared Successfully.'));
        return back();
    }

    public function database_upgrade($request)
    {
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);

        toastr_success(__('Database Upgraded Successfully.'));
        return back();
    }

}

