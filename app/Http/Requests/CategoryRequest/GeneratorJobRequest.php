<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class GeneratorJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name' => 'nullable|string',
            'generator_power' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'work_site_location' => 'nullable|string',
            'hour' => 'nullable|string',
            'day' => 'nullable|string',
            'month' => 'nullable|string',
            'search_radius' => 'nullable',
            'max_arrival_date' => 'nullable|date',
            'max_offer_deadline' => 'nullable|date',
            'additional_requirements' => 'nullable|string',
            'lat' => 'nullable',
            'long' => 'nullable',
            'isStopped' => 'nullable|in:0,1',
            'isSeen' => 'nullable|in:0,1',
        ];
    }
}
