<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    private function ditectSource($source){
        return match($source){
            "google" => "google_id",
            "facebook" => "facebook_id",
            "apple" => "apple_id",
        };
    }

    //social login
    public function social_login(Request $request)
    {
        $authorization = $request->header('secretKey');
        $secret_key = '$2y$10$GlEhJtlTAqv2rvQd2llgPeGGV8RT2Yap844OSazHfHlbU.0bvVTPm';

        if ($authorization !== $secret_key) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $requestData = $request->validate([
            'source' => 'required',
            'is_go_fb_ap_id' => 'required',
        ]);

        $user_type = 2;
        $user = User::select('id', 'email', 'username','user_type','first_name','last_name',$this->ditectSource($requestData['source']))
            ->where($this->ditectSource($requestData['source']), $requestData['is_go_fb_ap_id'])
            ->when($request->has('email'), function ($query) use ($request) {
                $query->orWhere('email', $request->email);
            })
            ->first();

        if (is_null($user)) {
            $nextValidation = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|unique:users',
            ]);

            // create username
            $username = strtolower($nextValidation['firstname'] . $nextValidation['lastname']) . rand(11111111,99999999);
            $checkUser = User::where('username', $username)->first();
            if (is_null($checkUser)) {
                $username .= rand(11111111,99999999);
            }

            $user = User::create([
                'first_name' => $nextValidation['firstname'] ?? '',
                'last_name' => $nextValidation['lastname'] ?? '',
                'email' => $nextValidation['email'],
                'username' => $username,
                'password' => Hash::make(Str::random(8)),
                'user_type' => $user_type,
                'terms_condition' => 1,
                'is_email_verified' => 1,
                'google_id' => $requestData['source'] === 'google' ? $requestData['is_go_fb_ap_id'] : null,
                'facebook_id' => $requestData['source'] === 'facebook' ? $requestData['is_go_fb_ap_id'] : null,
                'apple_id' => $requestData['source'] === 'apple' ? $requestData['is_go_fb_ap_id'] : null,
            ]);
        }
        // now need to find incomming is_go_fb_ap_id is exist or not based on incomming source
        if($user->{$this->ditectSource($requestData['source'])} !== $requestData['is_go_fb_ap_id']){
            $user->{$this->ditectSource($requestData['source'])} = $requestData['is_go_fb_ap_id'];
            $user->save();
        }

        $token = $user->createToken(Str::slug(get_static_option('site_title', 'xilancer')) . 'api_keys')->plainTextToken;
        return response()->json([
            'user_details' => $user,
            'token' => $token,
        ]);
    }
}
