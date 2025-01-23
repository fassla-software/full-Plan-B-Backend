<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use plugins\FormBuilder\SanitizeInput;

class FreelancerHandleWithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "gateway_id" => "required",
            "gateway_fields" => "required",
            "amount" => "required",
            "user_id" => "required",
            "status" => "required"
        ];
    }

    protected function prepareForValidation()
    {
        $fields = [];
        foreach($this->gateway_field ?? [] as $key => $value){
            $fields[$key] = SanitizeInput::esc_html($value);
        }

        if(get_static_option('withdraw_fee_type') == 'percentage'){
            $withdraw_fee = ($this->amount*get_static_option('withdraw_fee'))/100;
        }else{
            $withdraw_fee = get_static_option('withdraw_fee') ?? 0;
        }

        return $this->merge([
            "gateway_id" => $this->gateway_name,
            "gateway_fields" => serialize($fields),
            "amount" => $this->amount - $withdraw_fee,
            "user_id" => Auth::guard("web")->user()->id,
            "status" => "1"
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
