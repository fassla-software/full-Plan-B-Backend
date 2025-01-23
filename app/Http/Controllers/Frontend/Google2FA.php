<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FA extends Controller
{

    //client 2fa
    public function _2fa_client()
    {
        $user = Auth::guard('web')->user();
        $google_2fa_url = "";
        $secret_key = "";

        if(!is_null($user->google_2fa_secret)){
            $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google_2fa_url = $google_2fa->getQRCodeInline(get_static_option('site_title'),$user->email,$user->google_2fa_secret);
            $secret_key = $user->google_2fa_secret;
        }
        return view('frontend.user.client._2fa.-2fa',compact('user','secret_key','google_2fa_url'));
    }
    public function _2fa_enable_disable_client(Request $request)
    {
        $user = Auth::guard('web')->user();
        $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        if ($request->form_type === 'generate_secret_key'){
            User::where('id', $user->id)->update([
                    'google_2fa_secret' => $google_2fa->generateSecretKey()
                ]);
            return redirect()->route('client._2fa');
        }

        if ($request->form_type === 'enable_2fa') {
            $request->validate(['secret'=>'required']);
            if($google_2fa->verifyKey($user->google_2fa_secret,  $request->secret)){
                User::where('id',$user->id)->update([ 'google_2fa_enable_disable_disable' => 1]);

                (new Authenticator(request()))->login();

                toastr_success(__('Enable Google 2Fa Success'));
                return back();
            }
            toastr_error(__('Invalid verification Code, Please try again'));
            return back();
        }

        if($user->google_2fa_enable_disable_disable === 1){
            if (!(Hash::check($request->get('current-password'), $user->password))) {
                toastr_error(__('Current password is wrong. Please try again.'));
                return back();
            }
            User::where('id',$user->id)->update([ 'google_2fa_enable_disable_disable' => 0]);
        }
        return back();
    }
    public function _2fa_verify_code_client()
    {
        if (!empty(session()->get('google_2fa_secret')) && isset(session()->get('google_2fa_secret')['auth_passed'])){
            return redirect()->route('client.profile');
        }
        return view('frontend.user.client._2fa.-2fa-verify-code');
    }
    public function _2fa_verify_secret_code_client(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'one_time_password' => 'required|min:6'
            ]);
            $user = Auth::guard('web')->user();
            $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

            if ($google_2fa->verifyKey($user->google_2fa_secret, $request->one_time_password)){
                (new Authenticator(request()))->login();
                return redirect()->route('client.profile');
            }
            return redirect()->back()->with(['msg' => __('security code verify failed, please try again'),'type' => 'danger']);
        }
    }

    //freelancer 2fa
    public function _2fa_freelancer()
    {
        $user = Auth::guard('web')->user();
        $google_2fa_url = "";
        $secret_key = "";
        if(!is_null($user->google_2fa_secret)){
            $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google_2fa_url = $google_2fa->getQRCodeInline(get_static_option('site_title'),$user->email,$user->google_2fa_secret);
            $secret_key = $user->google_2fa_secret;
        }
        return view('frontend.user.freelancer._2fa.-2fa',compact(['user','secret_key','google_2fa_url']));
    }
    public function _2fa_enable_disable_freelancer(Request $request)
    {
        $user = Auth::guard('web')->user();
        $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        if ($request->form_type === 'generate_secret_key'){
            User::where('id', $user->id)->update([
                'google_2fa_secret' => $google_2fa->generateSecretKey()
            ]);
            return redirect()->route('freelancer._2fa');
        }

        if ($request->form_type === 'enable_2fa') {
            $request->validate(['secret'=>'required']);
            if($google_2fa->verifyKey($user->google_2fa_secret,  $request->secret)){
                User::where('id',$user->id)->update([ 'google_2fa_enable_disable_disable' => 1]);

                (new Authenticator(request()))->login();

                toastr_success(__('Enable Google 2Fa Success'));
                return back();
            }
            toastr_error(__('Invalid verification Code, Please try again'));
            return back();
        }

        if($user->google_2fa_enable_disable_disable === 1){
            if (!(Hash::check($request->get('current-password'), $user->password))) {
                toastr_error(__('Current password is wrong. Please try again.'));
                return back();
            }
            User::where('id',$user->id)->update([ 'google_2fa_enable_disable_disable' => 0]);
        }
        return back();
    }
    public function _2fa_verify_code_freelancer()
    {
        if (!empty(session()->get('google_2fa_secret')) && isset(session()->get('google_2fa_secret')['auth_passed'])){
            return redirect()->route('freelancer.dashboard');
        }
        return view('frontend.user.freelancer._2fa.-2fa-verify-code');
    }
    public function _2fa_verify_secret_code_freelancer(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'one_time_password' => 'required|min:6'
            ]);
            $user = Auth::guard('web')->user();
            $google_2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

            if ($google_2fa->verifyKey($user->google_2fa_secret, $request->one_time_password)){
                (new Authenticator(request()))->login();
                return redirect()->route('freelancer.profile');
            }
            return redirect()->back()->with(['msg' => __('security code verify failed, please try again'),'type' => 'danger']);
        }
    }

}
