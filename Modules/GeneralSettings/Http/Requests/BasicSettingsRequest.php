<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasicSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'site_title' => 'required|string',
                'site_footer_copyright' => 'required|string',
                'disable_user_email_verify' => 'nullable|string',
                'site_maintenance_mode' => 'nullable|string',
                'admin_loader_animation' => 'nullable|string',
                'site_loader_animation' => 'nullable|string',
                'site_force_ssl_redirection' => 'nullable|string',
                'site_google_captcha_enable' => 'nullable|string',
            ];
        } else {
            return [];
        }

    }

    public function messages()
    {
        return [
            'site_title.required' => __("Site title is required"),
            'site_footer_copyright.required' => __('Copyright Text is required')
        ];
    }
}
