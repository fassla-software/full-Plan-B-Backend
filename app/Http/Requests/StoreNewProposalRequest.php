<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewProposalRequest extends FormRequest
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
            'price' => 'required|string|min:0',
            'per' => 'required|in:day,week,year,month,hour',
            'current_location' => 'required|string|max:255',
            'offer_ends_at' => 'required|string',
            'other_terms' => 'nullable|string',

            // generator attrs
            'model' => 'nullable|string',
            'generator_power' => 'nullable|string',
            'max_number_of_continues_operating_houres' => 'nullable|string',
            'number_of_daily_operating_houres' => 'nullable|string',
            'generator_images' => 'nullable',

            // scaffolding attrs
            'time_required_for_on_site_installation' => 'nullable|string',
            'scaffolding_images' => 'nullable|string',
        ];
    }
}
