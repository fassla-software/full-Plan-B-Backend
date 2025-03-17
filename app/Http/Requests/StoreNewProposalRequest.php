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
        ];
    }
}
