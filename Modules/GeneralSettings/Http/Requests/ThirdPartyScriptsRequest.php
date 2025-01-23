<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThirdPartyScriptsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'site_third_party_tracking_code' => 'nullable|string',
                'site_google_analytics' => 'nullable|string',
                'site_google_captcha_v3_site_key' => 'nullable|string',
                'site_google_captcha_v3_secret_key' => 'nullable|string',
                'tawk_api_key' => 'nullable|string',
            ];
        }else{
            return [];
        }

    }
}
