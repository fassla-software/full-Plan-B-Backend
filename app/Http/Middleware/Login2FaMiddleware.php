<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Support\Google2FAAuthenticator;

class Login2FaMiddleware
{
    public function handle(Request $request, Closure $next){

        if ($request->path() === 'client/profile/logout'){
            return $next($request);
        }

        if ($request->path() === 'freelancer/profile/logout'){
            return $next($request);
        }

        // check user enabled 2fa or not
        if (\Auth::guard('web')->check()){
            $userInfo = \Auth::guard('web')->user();
            if ($userInfo->google_2fa_enable_disable_disable == 1){
                $authenticator = app(Google2FAAuthenticator::class)->boot($request);
                if ($authenticator->isAuthenticated()) {
                    return $next($request);
                }else{
                   return $userInfo->user_type == 1 ? redirect()->route('client._2fa.verify.code') : redirect()->route('freelancer._2fa.verify.code');
                }
            }

        }
        return $next($request);
    }

}
