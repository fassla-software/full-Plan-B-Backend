<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class CraneRentRequest extends FormRequest
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
            'lat' => 'nullable',
            'long' => 'nullable',
            'model' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'boom_length' => 'nullable|numeric|min:0',
            'truck_load_capacity' => 'nullable|numeric|min:0',
            'current_location' => 'nullable|string|max:255',
            'load_at_max_arm_height' => 'nullable|numeric|min:0',
            'load_at_max_arm_distance' => 'nullable|numeric|min:0',
            'additional_equipment_images' => 'nullable|array|max:5',
            'vehicle_license_front' => 'nullable|string',
            'vehicle_license_back' => 'nullable|string',
            'driver_license_front' => 'nullable|string',
            'driver_license_back' => 'nullable|string',
            'custom_conditions' => 'nullable|string',
            'load_data_documents' => 'nullable|array',
            'installation_time' => 'nullable|integer|min:0',
            'base_area_required' => 'nullable|numeric|min:0',
            'maximum_height' => 'nullable|numeric|min:0',
            'maximum_load_capacity' => 'nullable|numeric|min:0',
            'insurance_documents' => 'nullable|array',
            'actual_load_at_max_distance' => 'nullable|numeric|min:0',
            'operator_qualification_documents' => 'nullable|array',
        ];
    }
}
