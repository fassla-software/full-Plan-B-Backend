<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {

        $not_allow_path = [
            'admin',
            'freelancer',
            'client',
            'community/community/question-post',
            'community',
        ];

        $allow_path = [
            'admin/visited/os',
            'admin/visited/browser',
            'admin/visited/device',
            'admin/visited-url',
            'admin/media-upload/all',
            'freelancer/logout',
            'freelancer/dashboard/switch-profile',
            'client/logout',
            'client/dashboard/switch-profile',
            'admin/user/search/freelancer',
            'admin/user/search/client',
            'admin/user/delete/search-user',
            'admin/job/search-job',
            'admin',
            'freelancer',
            'client',
            'client/live/fetch-chat-freelancer-record',
            'client/live/message-send',
            'freelancer/live/fetch-chat-client-record',
            'freelancer/live/message-send',
            'freelancer/live/offer-send',
            'broadcasting/auth',
        ];
        $contains = Str::contains($request->path(), $not_allow_path);


        if(in_array('client/live/message-send',$allow_path) || in_array('freelancer/live/message-send',$allow_path) ){
            if($request->isMethod('POST') || $request->isMethod('PUT')) {
                if($request->routeIs(['client.message.send','freelancer.message.send'])){
                    if(!empty($request->file)){
                        return response()->json(['status'=>'image_not_allow_in_demo']);
                    }
                }
            }
        }

        if(in_array($request->path(),$allow_path)){
            return $next($request);
        }

        if($request->isMethod('POST') || $request->isMethod('PUT')) {
            if($contains && !in_array($request->path(),$allow_path)){
                if ($request->ajax()){
                    toastr_error(__('This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.'));
                    return back();
                }
                toastr_warning(__('This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.'));
                return back();
            }

        }

        if($request->ajax() && !$request->isMethod('GET')) {

            if($contains && !in_array($request->path(),$allow_path)){
                toastr_error(__('This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.'));
                return back();
            }
        }
        return $next($request);
    }
}