<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypographySettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')){
            return [
                'body_font_family' => 'required|string|max:191',
                'body_font_variant' => 'required',
                'heading_font' => 'nullable|string',
                'heading_font_family' => 'nullable|string|max:191',
                'heading_font_variant' => 'nullable',
            ];
        } else {
            return [];
        }
    }

    public function messages()
    {
        return [
            'body_font_family.required' => __("Body fond family is required"),
            'body_font_variant.required' => __('Body font variant is required')
        ];
    }
}

