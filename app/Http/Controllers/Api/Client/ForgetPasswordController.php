<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    //forget password otp send
    public function forget_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = User::select('email','email_verify_token')->where('email',$request->email)->first();
        if($email){
            //send otp mail
            $otp_code = sprintf("%d", random_int(123456, 999999));
            try {
                Mail::to($email->email)->send(new BasicMail([
                    'subject' =>  __('Otp Email'),
                    'message' => __('Your otp code').' '.$otp_code
                ]));
            }
            catch (\Exception $e) {}

            User::where('email',$request->email)->update(['email_verify_token'=>$otp_code]);

            return response()->json([
                'email'=>$request->email,
                'msg'=>__('OTP code is send to your email. Please check')
            ]);
        }
        return response()->json([
            'msg'=>__('Email not found please enter a valid email')
        ]);
    }

    //confirm email by otp
    public function confirm_email_by_otp(Request $request){

        $request->validate(['email' => 'required|email','otp'=>'required']);

        $user_email = $request->email;
        $user_otp = $request->otp;

        $find_email = User::where('email',$user_email)->where('email_verify_token',$user_otp)->first();
        if($find_email){
            return response()->json([
                'email'=>$find_email->email,
                'otp'=>$user_otp,
                'msg'=>__('Email is confirmed by otp code')
            ]);
        }
        return response()->json([
            'msg'=>__('OTP is wrong. Please enter a valid otp')
        ]);
    }

    //reset password
    public function reset_password(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:191',
            'confirm_password' => 'required|min:6|max:191',
        ]);

        $user_email = $request->email;
        $user_otp = $request->otp;

        $user = User::select(['email','user_type','email_verify_token'])->where('email',$user_email)->where('email_verify_token',$user_otp)->first();

        if($user){
            if ($request->password == $request->confirm_password) {
                User::where('email',$user_email)
                    ->where('email_verify_token',$user_otp)
                    ->update(['password' => Hash::make($request->password)]);
                return response()->json(['msg' => __('Password successfully updated')]);
            }
            return response()->json(['msg' => __('Password does not match')]);
        }
        return response()->json(['msg' => ('User not found')]);
    }
}
