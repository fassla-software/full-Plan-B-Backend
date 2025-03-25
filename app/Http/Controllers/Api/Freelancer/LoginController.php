<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:191',
            'password' => 'required|min:6|max:191',
        ]);

        $login_type = 'phone';
        $user_type = 2;

        $user = User::select('id', 'email', 'phone', 'user_type', 'password', 'username', 'is_email_verified', 'email_verify_token')
            ->where([$login_type => $request->phone, 'user_type' => $user_type])
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => sprintf(__('Invalid %s or Password'), ucFirst($login_type))
            ]);
        } else {
            $token = $user->createToken(Str::slug(get_static_option('site_title', 'xilancer')) . 'api_keys')->plainTextToken;
            return response()->json([
                'msg' => __('Login Success'),
                'user' => $user,
                'token' => $token,
            ]);
        }
    }

    public function logout()
    {
        auth("sanctum")->user()->tokens()->delete();
        return response()->json([
            'message' => __('Logout success'),
        ]);
    }
}
