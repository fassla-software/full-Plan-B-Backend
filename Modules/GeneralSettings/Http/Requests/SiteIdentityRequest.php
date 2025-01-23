<?php


namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteIdentityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'site_logo' => 'nullable|string',
                'site_favicon' => 'nullable|string',
            ];
        }
        return [];
    }
}
