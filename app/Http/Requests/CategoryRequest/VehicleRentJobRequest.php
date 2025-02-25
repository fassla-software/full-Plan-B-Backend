<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRentJobRequest extends FormRequest
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
            'sub_category_id'=> 'nullable|exists:sub_categories,id',
            'name' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'required_load_capacity' => 'nullable|integer',
            'required_work_location' => 'nullable|string|max:255',
            'required_rental_duration' => 'nullable|string',
            'search_radius' => 'nullable',
            'max_arrival_date' => 'nullable|string',
            'max_offer_deadline' => 'nullable|string',
            'work_description' => 'nullable',
            'safety_compliant' => 'nullable|boolean',
            'environmental_compliant' => 'nullable|boolean',
            'has_night_lighting' => 'nullable|boolean',
            'vehicle_type' => 'nullable|string',
            'lat' => 'nullable',
            'long' => 'nullable',
        ];
    }
}
