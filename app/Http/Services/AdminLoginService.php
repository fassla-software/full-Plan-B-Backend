<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;

class AdminLoginService
{

    public function adminLogin()
    {
        $email_or_username = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::guard('admin')->attempt([$email_or_username => $this->username, 'password' => $this->password], $this->get('remember'))) {
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'status' => 'ok'
            ]);
        }
        return response()->json([
            'msg' => sprintf(__('Your %s or Password Is Wrong !!'),$email_or_username),
            'status' => 'not_ok'
        ]);
    }
}
