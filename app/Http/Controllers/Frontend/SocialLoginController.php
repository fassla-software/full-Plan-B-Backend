<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function facebook_redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebook_callback()
    {
        try {
            $user_fb_details = Socialite::driver('facebook')->user();
            $user_details = User::where('email', $user_fb_details->getEmail())->first();

            if ($user_details) {
                Auth::login($user_details);
                if($user_details->user_type == 1){
                    return redirect()->intended('client/dashboard/info');
                }else{
                    return redirect()->intended('freelancer/dashboard/info');
                }
            } else {
                $new_user = User::create([
                    'username' => 'fb_' . explode('@', $user_fb_details->getEmail())[0],
                    'first_name' => $user_fb_details->getName(),
                    'last_name' => $user_fb_details->getName(),
                    'email' => $user_fb_details->getEmail(),
                    'user_type' => 1,
                    'is_email_verified' => 1,
                    'facebook_id' => $user_fb_details->getId(),
                    'password' => Hash::make(\Illuminate\Support\Str::random(8))
                ]);

                Auth::login($new_user);
                return redirect()->intended('client/dashboard/info');
            }
        } catch (\Exception $e) {
            return redirect()->intended('login')->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_callback()
    {
        try {
            $user_go_details = Socialite::driver('google')->user();
            $user_details = User::where('email', $user_go_details->getEmail())->first();

            if ($user_details) {
                Auth::login($user_details);
                if($user_details->user_type == 1){
                    return redirect()->intended('client/dashboard/info');
                }else{
                    return redirect()->intended('freelancer/dashboard/info');
                }
            } else {
                $new_user = User::create([
                    'username' => 'go_' . explode('@', $user_go_details->getEmail())[0],
                    'first_name' => $user_go_details->getName(),
                    'last_name' => $user_go_details->getName(),
                    'email' => $user_go_details->getEmail(),
                    'user_type' => 1,
                    'is_email_verified' => 1,
                    'google_id' => $user_go_details->getId(),
                    'password' => Hash::make(\Illuminate\Support\Str::random(8))
                ]);

                Auth::login($new_user);
                return redirect()->intended('client/dashboard/info');
            }
        } catch (\Exception $e) {
            return redirect()->intended('login')->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }
}
