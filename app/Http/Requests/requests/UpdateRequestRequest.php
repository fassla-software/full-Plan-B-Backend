<?php

namespace App\Http\Requests\requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
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
            'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'lat' => 'nullable|numeric|between:-90,90',
            'long' => 'nullable|numeric|between:-180,180',
            'work_site_location' => 'nullable|string',
            'hour' => 'nullable|string|max:255',
            'day' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:255',
            'search_radius' => 'nullable|integer|min:0',
            'max_arrival_date' => 'nullable|date',
            'max_offer_deadline' => 'nullable|date',
            'size' => 'nullable|string|max:255',
            'attachments' => 'nullable|string|max:255',
            'flatbed_load_description' => 'nullable|string',
            'flatbed_loading_location' => 'nullable|string',
            'flatbed_destination_location' => 'nullable|string',
            'asphalt_scraper_movement' => 'nullable|string',
            'safety_compliant' => 'nullable|boolean',
            'environmental_compliant' => 'nullable|boolean',
            'has_night_lighting' => 'nullable|boolean',
            'additional_requirements' => 'nullable|string',
            'isStopped' => 'nullable|integer|in:0,1',
            'isSeen' => 'nullable|integer|in:0,1',
        ];
    }
}
