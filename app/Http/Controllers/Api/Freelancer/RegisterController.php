<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Wallet\Entities\Wallet;

class RegisterController extends Controller
{
    //register
    public function register(Request $request)
    {
           //laravel validation
            $request->validate([
                'first_name' => 'required|max:191',
                'last_name' => 'required|max:191',
                'email' => 'required|email|unique:users|max:191',
                'username' => 'required|unique:users|max:191',
                'phone' => 'required|unique:users|max:191',
                'password' => 'required|min:6|max:191',
                'confirm_password' => 'required|min:6|max:191',
            ]);

            //password match validation
            if($request->password != $request->confirm_password){
                return response()->json(['msg'=>__('Password does not match')]);
            }

            $email_verify_tokn = sprintf("%d", random_int(123456, 999999));
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => 2,
                'terms_conditions' =>1,
                'email_verify_token'=> $email_verify_tokn,
            ]);

        if (!is_null($user)) {
            //create freelancer wallet
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'remaining_balance' => 0,
                'withdraw_amount' => 0,
                'status' => 1
            ]);

            //send register mail
            try {
                $message = get_static_option('user_register_message') ?? __('You have successfully registered as a freelancer');
                $message = str_replace(["@name","@email","@username","@password"],[$user->first_name.' '.$user->last_name, $user->email, $user->username, $request->password], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_register_subject') ?? __('New User Register Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}


            //send register welcome mail
            try {
                $message = get_static_option('user_register_welcome_message') ?? __('Your registration successfully completed.');
                $message = str_replace(["@name","@email","@username","@password","@userType"],[$user->first_name.' '.$user->last_name, $user->email, $user->username, $request->password, 'freelancer'], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_register_welcome_subject') ?? __('User Register Welcome Email'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}


            //send otp mail
            try {
                Mail::to($user->email)->send(new BasicMail([
                    'subject' =>  __('Otp Email'),
                    'message' => __('Your otp code').' '.$email_verify_tokn
                ]));
            }
            catch (\Exception $e) {}

            $token = $user->createToken(Str::slug(get_static_option('site_title', 'xilancer')) . 'api_keys')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'status' => 'success',
            ]);
        }
    }

    //send otp
    public function resend_otp(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $otp_code = sprintf("%d", random_int(123456, 999999));
        $user_email = User::where('email', $request->email)->first();

        if (!empty($user_email)) {
            try {
                Mail::to($request->email)->send(new BasicMail([
                    'subject' =>  __('Otp Email'),
                    'message' => __('Your otp code').' '.$otp_code
                ]));
            } catch (\Exception $e) {
                return response()->error([
                    'message' => __($e->getMessage()),
                ]);
            }
            User::where('email',$user_email->email)->update(['email_verify_token'=>$otp_code]);
            return response()->json(['email' => $request->email,'otp' => $otp_code]);
        }else{
            return response()->json(['message' => __('Email Does not Exists')]);
        }

    }

    public function email_verify(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'otp_code' => 'required|integer',
        ]);

        $user = User::where(['email_verify_token' => $request->otp_code,'id' => $request->user_id])->first();

        if(!empty($user)){
            User::where('id', $request->user_id)->update(['is_email_verified'=>1]);
            return response()->json(['msg' => __('Email verification success.')]);
        }
        return response()->json(['msg' => __('Your verification code is wrong.')]);
    }

}
