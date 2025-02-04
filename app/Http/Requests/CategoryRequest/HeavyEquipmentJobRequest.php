<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class HeavyEquipmentJobRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'work_site_location' => 'nullable|string',
            'rental_duration' => 'nullable|string',
            'search_radius' => 'nullable|integer',
            'max_arrival_date' => 'nullable|date',
            'max_offer_deadline' => 'nullable|date',
            'size' => 'nullable|string',
            'attachments' => 'nullable|array',
            'flatbed_load_description' => 'nullable|string',
            'flatbed_loading_location' => 'nullable|string',
            'flatbed_destination_location' => 'nullable|string',
            'asphalt_scraper_movement' => 'nullable|string',
            'safety_compliant' => 'nullable|boolean',
            'environmental_compliant' => 'nullable|boolean',
            'has_night_lighting' => 'nullable|boolean',
            'additional_requirements' => 'nullable|string',
        ];
    }
}
