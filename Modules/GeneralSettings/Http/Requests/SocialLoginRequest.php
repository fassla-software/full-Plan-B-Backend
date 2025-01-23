<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'facebook_client_id' => 'nullable|string',
                'facebook_client_secret' => 'nullable|string',
                'google_client_id' => 'nullable|string',
                'google_client_secret' => 'nullable|string',
            ];
        }else{
            return [];
        }

    }
}
