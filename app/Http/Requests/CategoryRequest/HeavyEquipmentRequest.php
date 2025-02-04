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
            'category_id' => 'nullable|max:50',
            'size' => 'nullable|string',
            'name' => 'nullable|string',
            'model' => 'nullable|max:50',
            'year_of_manufacture' => 'nullable|max:4',
            'current_equipment_location' => 'nullable|string',


            'data_certificate_image' => 'nullable',
            'driver_license_front_image' => 'nullable',
            'driver_license_back_image' => 'nullable',
            'additional_equipment_images' => 'nullable|array|max:5',
            'special_rental_conditions' => 'nullable|max:500',
            'flatbed_license_front_image' => 'required_if:equipment_type,equipmentTransportFlatbed',
            'flatbed_license_back_image' => 'required_if:equipment_type,equipmentTransportFlatbed',

            'blade_width_near_digging_arm' => 'nullable:equipment_type,backhoeLoader|numeric',
            'add_bucket' => 'nullable:equipment_type,backhoeLoader,excavator|string',
            'sprinkler_system_type' => 'nullable:equipment_type,bitumenSprayerTruck|string',
            'tank_capacity' => 'nullable:equipment_type,bitumenSprayerTruck|numeric',
            'panda_width' => 'nullable:equipment_type,bitumenSprayerTruck|max:4',
            'has_bitumen_temp_gauge' => 'nullable:equipment_type,bitumenSprayerTruck|boolean',
            'has_bitumen_level_gauge' => 'nullable:equipment_type,bitumenSprayerTruck|boolean',
            'max_equipment_load' => 'nullable:equipment_type,telehandler,forklift|numeric',
            'boom_length' => 'nullable:equipment_type,telehandler|numeric',
            'load_at_max_boom_height' => 'nullable:equipment_type,telehandler|numeric',
            'load_at_max_horizontal_boom_extension' => 'nullable:equipment_type,telehandler|numeric',
            'tractor_license_front_image' => 'nullable:equipment_type,agriculturalTractor',
            'tractor_license_back_image' => 'nullable:equipment_type,agriculturalTractor',
            'engine_power' => 'nullable:equipment_type,grader|max:3',
            'blade_width' => 'nullable:equipment_type,grader|max:4',
            'blade_type' => 'nullable:equipment_type,grader|string',
            'moves_on' => 'nullable:equipment_type,finisher,loader,asphaltScraper|string',
            'scraper_width' => 'nullable:equipment_type,asphaltScraper|numeric|max:3',
        ];
    }
}
