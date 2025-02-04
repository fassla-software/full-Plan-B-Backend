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
            'name' => 'required|string',
            'category_id' => 'required|integer|max:50',
            'vehicle_load' => 'required|numeric',
            'model' => 'required|max:4',
            'current_vehicle_location' => 'required|string',
            'vehicle_license_front_image' => 'required|image',
            'vehicle_license_back_image' => 'required|image',
            'driver_license_front_image' => 'required|image',
            'driver_license_back_image' => 'required|image',
            'additional_vehicle_images' => 'required|array|max:5',
            'additional_vehicle_images.*' => 'image',
            'comment' => 'nullable|string|max:500',

            // Conditional required fields based on vehicle type
            'has_tank_discharge_pump' => 'required_if:equipment_type,potableWaterTanker|boolean',
            'has_band_sprinkler_bar' => 'required_if:equipment_type,constructionWaterTanker|boolean',
            'has_discharge_pump_with_liters_meter' => 'required_if:equipment_type,petroleumMaterialTanker|boolean',
        ];
    }
}
