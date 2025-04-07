<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class ScaffoldingRequest extends FormRequest
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
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'category_id' => 'nullable|max:50',
            'lat' => 'nullable',
            'long' => 'nullable',
            'special_rental_conditions' => 'nullable|max:500',
            'current_equipment_location' => 'nullable|string',
            'equipment_images' => 'nullable|string',
        ];
    }
}
