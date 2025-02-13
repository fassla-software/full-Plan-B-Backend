<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class HeavyEquipmentRequest extends FormRequest
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
            'category_id' => 'nullable|max:50',
            'size' => 'nullable|string',
            'name' => 'nullable|string',
            'model' => 'required|max:50',
            'year_of_manufacture' => 'required|max:4',
            'current_equipment_location' => 'required|string',


            'data_certificate_image' => 'required',
            'driver_license_front_image' => 'required',
            'driver_license_back_image' => 'required',

            'additional_equipment_images' => 'nullable|array|max:5',
            'special_rental_conditions' => 'nullable|max:500',

            'flatbed_license_front_image' => 'required_if:equipment_type,equipmentTransportFlatbed',
            'flatbed_license_back_image' => 'required_if:equipment_type,equipmentTransportFlatbed',

            'blade_width_near_digging_arm' => 'required_if:equipment_type,backhoeLoader|numeric',
            'add_bucket' => 'required_if:equipment_type,backhoeLoader,excavator|string',
            'sprinkler_system_type' => 'required_if:equipment_type,bitumenSprayerTruck|string',
            'tank_capacity' => 'required_if:equipment_type,bitumenSprayerTruck|numeric',
            'panda_width' => 'required_if:equipment_type,bitumenSprayerTruck|max:4',
            'has_bitumen_temp_gauge' => 'required_if:equipment_type,bitumenSprayerTruck|boolean',
            'has_bitumen_level_gauge' => 'required_if:equipment_type,bitumenSprayerTruck|boolean',
            'max_equipment_load' => 'required_if:equipment_type,telehandler,forklift|numeric',
            'boom_length' => 'required_if:equipment_type,telehandler|numeric',
            'load_at_max_boom_height' => 'required_if:equipment_type,telehandler|numeric',
            'load_at_max_horizontal_boom_extension' => 'required_if:equipment_type,telehandler|numeric',
            'tractor_license_front_image' => 'required_if:equipment_type,agriculturalTractor',
            'tractor_license_back_image' => 'required_if:equipment_type,agriculturalTractor',
            'engine_power' => 'required_if:equipment_type,grader|max:3',
            'blade_width' => 'required_if:equipment_type,grader|max:4',
            'blade_type' => 'required_if:equipment_type,grader|string',
            'moves_on' => 'required_if:equipment_type,finisher,loader,asphaltScraper|string',
            'scraper_width' => 'required_if:equipment_type,asphaltScraper|numeric|max:3',
        ];
    }
}
