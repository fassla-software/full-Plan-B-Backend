<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGatewayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => "sometimes",
            "name" => "required|string",
            "field" => "required|string",
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            "field" => serialize($this->field),
            "name" => $this->name
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
