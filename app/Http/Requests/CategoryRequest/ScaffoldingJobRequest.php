<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class ScaffoldingJobRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'lat' => 'nullable',
            'long' => 'nullable',
            'hour' => 'nullable|string',
            'day' => 'nullable|string',
            'month' => 'nullable|string',
            'height_of_work_area_on_the_wall' => 'nullable|string',
            'display_workspace_on_the_wall' => 'nullable|string',
            'height_of_beginning_of_work_area_the_wall_from_floor' => 'nullable|string',
            'scaffolding_base_mounting_floor_pictures' => 'nullable|string',
            'work_wall_pictures' => 'nullable|string',
            'work_site_location' => 'nullable|string',
            'search_radius' => 'nullable',
            'max_arrival_date' => 'nullable|date',
            'max_offer_deadline' => 'nullable|date',
            'additional_requirements' => 'nullable|string',
            'isStopped' => 'nullable|in:0,1',
            'isSeen' => 'nullable|in:0,1',
        ];
    }
}
