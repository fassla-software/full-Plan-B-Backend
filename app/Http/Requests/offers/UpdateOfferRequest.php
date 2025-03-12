<?php

namespace App\Http\Requests\offers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
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
    public function rules()
    {
        return [
            'price'            => ['nullable', 'numeric', 'min:0'],
            'per'              => ['nullable', 'string', 'in:day,month,hour,week'],
            'current_location' => ['nullable', 'string', 'max:255'],
            'offer_ends_at'    => ['nullable', 'date', 'after_or_equal:today'],
            'other_terms'      => ['nullable', 'string'],
            'isSeen'           => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
