<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class CraneRentJobRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'user_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'worksite_location' => 'nullable|string|max:255',
            'hour' => 'nullable|string',
            'day' => 'nullable|string',
            'month' => 'nullable|string',
            'search_range_around_worksite' => 'nullable|numeric|min:0',
            'max_arrival_date' => 'nullable|string|max:255',
            'max_offer_deadline' => 'nullable|string|max:255',
            'additional_requirements' => 'nullable|string',
            'number_of_loading_points' => 'nullable|integer|min:1',
            'load_image' => 'nullable|string',
            'load_weight' => 'nullable|numeric|min:0',
            'load_location' => 'nullable|string|max:255',
            'number_of_load_destinations' => 'nullable|integer|min:1',
            'unloading_location' => 'nullable|string|max:255',
            'load_start_time' => 'nullable|string|max:255',
            'search_range_around_loading_location' => 'nullable|numeric|min:0',
            'offer_submission_deadline' => 'nullable|string|max:255',
            'required_height' => 'nullable|numeric|min:0',
            'required_load' => 'nullable|numeric|min:0',
            'furniture_lifting_to_floor' => 'nullable|integer|min:0',
            'safety_compliant' => 'nullable|boolean',
            'environmental_compliant' => 'nullable|boolean',
            'has_night_lighting' => 'nullable|boolean',
            'lat' => 'nullable',
            'long' => 'nullable',
            'search_radius' => 'nullable',
        ];
    }
}
