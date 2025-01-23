<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->isMethod('post')){
            return [
                'username' => 'required|string',
                'password' => 'required|min:6'
            ];
        }else{
            return  [];
        }

    }

    public function messages()
    {
        $email_or_username = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return[
            'username.required' => sprintf(__('%s required'),$email_or_username),
            'password.required' => __('password required')
        ];
    }
}
