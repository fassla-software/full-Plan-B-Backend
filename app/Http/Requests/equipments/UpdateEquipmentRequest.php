<?php

namespace App\Http\Requests\equipments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sub_category_id' => ['nullable', 'integer', 'exists:sub_categories,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'lat' => ['nullable', 'numeric'],
            'long' => ['nullable', 'numeric'],
            'size' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year_of_manufacture' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'moves_on' => ['nullable', 'string', 'max:255'],
            'current_equipment_location' => ['nullable', 'string', 'max:255'],

            'data_certificate_image' => ['nullable', 'string'],
            'driver_license_front_image' => ['nullable', 'string'],
            'driver_license_back_image' => ['nullable', 'string'],
            'tractor_license_front_image' => ['nullable', 'string'],
            'tractor_license_back_image' => ['nullable', 'string'],
            'flatbed_license_front_image' => ['nullable', 'string'],
            'flatbed_license_back_image' => ['nullable', 'string'],
            'additional_equipment_images' => ['nullable', 'string'],

            'special_rental_conditions' => ['nullable', 'string'],
            'blade_width' => ['nullable', 'numeric'],
            'blade_width_near_digging_arm' => ['nullable', 'numeric'],
            'add_bucket' => ['nullable', 'boolean'],
            'engine_power' => ['nullable', 'integer', 'min:0'],
            'scraper_width' => ['nullable', 'numeric'],
            'sprinkler_system_type' => ['nullable', 'string', 'max:255'],
            'tank_capacity' => ['nullable', 'numeric', 'min:0'],
            'panda_width' => ['nullable', 'numeric', 'min:0'],
            'has_bitumen_temp_gauge' => ['nullable', 'boolean'],
            'has_bitumen_level_gauge' => ['nullable', 'boolean'],
            'max_equipment_load' => ['nullable', 'numeric', 'min:0'],
            'boom_length' => ['nullable', 'numeric', 'min:0'],
            'load_at_max_boom_height' => ['nullable', 'numeric', 'min:0'],
            'load_at_max_horizontal_boom_extension' => ['nullable', 'numeric', 'min:0'],
            'max_lifting_point' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
