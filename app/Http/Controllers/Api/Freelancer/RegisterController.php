<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Models\User;
use App\Mail\BasicMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Wallet\Entities\Wallet;
use App\Http\Controllers\Controller;
use App\Services\users\UsersService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\users\UserRegisterRequest;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UsersService $userService)
    {
        $this->userService = $userService;
    }

    public function register(UserRegisterRequest $request)
    {
        $email_verify_tokn = sprintf("%d", random_int(123456, 999999));

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => 2,
            'terms_conditions' => 1,
            'email_verify_token' => $email_verify_tokn,
        ]);

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
            $this->userService->sendRegisterEmail($user, $request->password);
            $this->userService->sendWelcomeEmail($user, $request->password);
            $this->userService->sendOTPEmail($user->email, $email_verify_tokn);
        } catch (\Exception $e) {
            \Log::error("email sending failed", ["message" => $e->getMessage()]);
        }

        $token = $user->createToken(Str::slug(get_static_option('site_title', 'xilancer')) . 'api_keys')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
            'status' => 'success',
        ]);
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
                    'message' => __('Your otp code') . ' ' . $otp_code
                ]));
            } catch (\Exception $e) {
                return response()->error([
                    'message' => __($e->getMessage()),
                ]);
            }
            User::where('email', $user_email->email)->update(['email_verify_token' => $otp_code]);
            return response()->json(['email' => $request->email, 'otp' => $otp_code]);
        } else {
            return response()->json(['message' => __('Email Does not Exists')]);
        }
    }

    public function email_verify(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'otp_code' => 'required|integer',
        ]);

        $user = User::where(['email_verify_token' => $request->otp_code, 'id' => $request->user_id])->first();

        if (!empty($user)) {
            User::where('id', $request->user_id)->update(['is_email_verified' => 1]);
            return response()->json(['msg' => __('Email verification success.')]);
        }
        return response()->json(['msg' => __('Your verification code is wrong.')]);
    }
}
