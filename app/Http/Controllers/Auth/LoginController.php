<?php

namespace App\Http\Controllers\Auth;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Services\AdminLoginService;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Mail\BasicMail;


class LoginController extends Controller
{
    use AuthenticatesUsers;

//    protected $redirectTo = '/';
    public function redirectTo()
    {
        return route('homepage');
    }

    public function __construct()
    {
//        $this->middleware()->except(['logout','userLogin']);
    }

    public function username()
    {
        return 'username';
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        if($request->isMethod('post')){
            $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            if (Auth::guard('admin')->attempt([$email_or_username => $request->username, 'password' => $request->password], $request->get('remember'))) {
                return response()->json([
                    'msg' => __('Login Success Redirecting'),
                    'status' => 'ok',
                    'type' => 'success'
                ]);
            }
            return response()->json([
                'msg' => sprintf(__('Your %s or Password Is Wrong !!'),$email_or_username),
                'status' => 'not_ok',
                'type' => 'danger'
            ]);
        }
        return view('backend.pages.auth.login');
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with(['msg'=>__('You Logged Out !!'),'type'=> 'danger']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */

    public function userLogin(Request $request)
    {
        if($request->isMethod('post')){
            $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|min:6'
            ],
                [
                    'username.required' => sprintf(__('%s required'),$email_or_username),
                    'password.required' => __('password required')
                ]);

            if (Auth::guard('web')->attempt([$email_or_username => $request->username, 'password' => $request->password],$request->get('remember'))){
                if(Auth::user()->user_active_inactive_status == 1){

                    if(Auth::user()->user_type == 1){
                        //security manage
                        if(moduleExists('SecurityManage')){
                            LogActivity::addToLog('User logged in','Client');
                        }
                        //session value is for profile switch to check all order page Customer or Client text
                        Session::put('user_role', 'client');
                        return response()->json([
                            'msg' => __('Login Success Redirecting'),
                            'type' => 'success',
                            'status' => 'client-login'
                        ]);
                    }else{
                        //security manage
                        if(moduleExists('SecurityManage')){
                            LogActivity::addToLog('User logged in','Freelancer');
                        }
                        //session value is for profile switch to check all order page Customer or Client text
                        Session::put('user_role', 'freelancer');
                        return response()->json([
                            'msg' => __('Login Success Redirecting'),
                            'type' => 'success',
                            'status' => 'freelancer-login'
                        ]);
                    }

                }else{
                    return response()->json([
                        'msg' => sprintf(__('Your account is inactive please contact to admin !!'),$email_or_username),
                        'type' => 'danger',
                        'status' => 'not_ok'
                    ]);
                }
            }
            return response()->json([
                'msg' => sprintf(__('Your %s or Password Is Wrong !!'),$email_or_username),
                'type' => 'danger',
                'status' => 'not_ok'
            ]);
        }
        return view('frontend.user.user-login');
    }

    public function forgetPassword(Request $request){

        if($request->isMethod('post')){
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

                Session::put('email',$email->email);
                return redirect()->route('user.forgot.password.otp');
            }
            return back()->with(toastr_error(__('Email not found please enter a valid email')));
        }
        return view('frontend.user.forgot-password');
    }

    public function passwordResetOtp(Request $request){

        if($request->isMethod('post')){
            $user_email = session()->get('email');

            $find_email = User::where('email',$user_email)->where('email_verify_token',$request->otp)->first();
            if($find_email){
                Session::put('user_email',$find_email->email);
                Session::put('user_otp',$request->otp);
                return redirect()->route('user.forgot.password.reset');
            }
        }
        return view('frontend.user.password-reset-otp');
    }

    public function passwordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|min:6|max:191',
                'confirm_password' => 'required|min:6|max:191',
            ]);

            $user_email = session()->get('user_email');
            $user_otp = session()->get('user_otp');

            $user = User::select(['email','user_type','email_verify_token'])->where('email',$user_email)->where('email_verify_token',$user_otp)->first();

            if($user){
                if ($request->password == $request->confirm_password) {
                    User::where('email',$user_email)
                        ->where('email_verify_token',$user_otp)
                        ->update(['password' => Hash::make($request->password)]);
                    return redirect()->route('user.login');
                }
                return back()->with(toastr_warning(__('Password does not match')));
            }else{
                return back()->with(toastr_warning(__('User not found')));
            }
        }
        return view('frontend.user.password-reset');
    }
}
