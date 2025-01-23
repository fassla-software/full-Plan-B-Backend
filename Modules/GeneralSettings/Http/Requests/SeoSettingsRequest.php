<?php

namespace Modules\GeneralSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'site_meta_tags' => 'nullable|string',
                'site_meta_description' => 'nullable|string',
                'og_meta_title' => 'nullable|string',
                'og_meta_description' => 'nullable|string',
                'og_meta_site_name' => 'nullable|string',
                'og_meta_url' => 'nullable|string',
                'og_meta_image' => 'nullable|string',
            ];
        } else {
            return [];
        }

    }
}
