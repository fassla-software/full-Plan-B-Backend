<?php

namespace Modules\Chat\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PusherSettingsController extends Controller
{

    public function pusher_settings(Request $request)
    {
        if($request->isMethod('post')){
            $pusher = $request->validate([
                "PUSHER_APP_ID" => "required",
                "PUSHER_APP_KEY" => "required",
                "PUSHER_APP_SECRET" => "required",
                "PUSHER_APP_CLUSTER" => "required",
            ]);

            setEnvValue($pusher);
            toastr_success(__('Pusher Settings Updated Successfully.'));
            return back();
        }

        return view('chat::admin.pusher-settings');
    }

}
