<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class GeneratorRequest extends FormRequest
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
            'name' => 'nullable|string',
            'model' => 'nullable|string',
            'generator_power' => 'nullable|string',
            'maximum_number_of_continuous_operating_hours' => 'nullable|string',
            'year_of_manufacture' => 'nullable|max:4',
            'current_generator_location' => 'nullable|string',
            'generator_image' => 'nullable|max:500',
            'lat' => 'nullable',
            'long' => 'nullable',
            'special_rental_conditions' => 'nullable|string|max:500',
        ];
    }
}
