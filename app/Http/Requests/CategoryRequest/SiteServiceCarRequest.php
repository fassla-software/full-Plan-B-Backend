<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class SiteServiceCarRequest extends FormRequest
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
            'category_id' => 'required|integer',
            'size' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year_of_manufacture' => 'nullable|integer',
            'moves_on' => 'nullable|string|max:255',
            'current_equipment_location' => 'nullable|string|max:255',
            'data_certificate_image'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
            'driver_license_front_image'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
            'driver_license_back_image'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
            'additional_equipment_images'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
            'special_rental_conditions' => 'nullable|string',
            'blade_width'=>'nullable|numeric',
            'blade_width_near_digging_arm'=>'nullable|numeric',
            'engine_power'=>'nullable|numeric',
            'milling_blade_width'=>'nullable|numeric',
            'sprinkler_system_type'=>'nullable|string|max:191',
            'tank_capacity'=>'nullable|numeric',
            'panda_width'=>'nullable|numeric',
            'has_bitumen_temp_gauge'=>'nullable|boolean',
            'has_bitumen_level_gauge'=>'nullable|boolean',
            'paving_range'=>'nullable|string|max:191',
            'max_equipment_load'=>'nullable|numeric',
            'boom_length'=>'nullable|numeric',
            'load_at_max_boom_height'=>'nullable|numeric',
            'load_at_max_horizontal_boom_extension'=>'nullable|numeric',
            'max_lifting_point'=>'nullable|numeric',
            'attachments'=>'nullable|string',
            'has_tank_discharge_pump'=>'nullable|boolean',
            'has_band_sprinkler_bar'=>'nullable|boolean',
            'has_discharge_pump_with_liters_meter'=>'nullable|boolean'
        ];
    }
}
