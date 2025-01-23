<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'site_main_color_one' => 'nullable|string',
                'site_main_color_two' => 'nullable|string',
                'site_main_color_three' => 'nullable|string',
            ];
        } else {
            return [];
        }

    }
}
