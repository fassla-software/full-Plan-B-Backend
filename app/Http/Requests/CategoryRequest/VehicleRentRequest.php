<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRentRequest extends FormRequest
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
            'name' => 'nullable|string',
            'category_id' => 'nullable|integer|max:50',
            'sub_category_id' => 'nullable|integer|max:50',
            'vehicle_load' => 'nullable|numeric',
            'model' => 'nullable',
            'current_vehicle_location' => 'nullable|string',
            'vehicle_license_front_image' => 'nullable',
            'vehicle_license_back_image' => 'nullable',
            'driver_license_front_image' => 'nullable',
            'driver_license_back_image' => 'nullable',
            'additional_vehicle_images' => 'nullable|array|max:5',
            'comment' => 'nullable|string|max:500',
            'lat' => 'nullable',
            'long' => 'nullable',

            // Conditional required fields based on vehicle type
            'has_tank_discharge_pump' => 'required_if:equipment_type,potableWaterTanker|boolean',
            'has_band_sprinkler_bar' => 'required_if:equipment_type,constructionWaterTanker|boolean',
            'has_discharge_pump_with_liters_meter' => 'required_if:equipment_type,petroleumMaterialTanker|boolean',
        ];
    }
}
