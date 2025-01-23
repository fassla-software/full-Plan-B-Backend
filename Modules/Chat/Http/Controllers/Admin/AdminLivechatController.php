<?php

namespace Modules\Chat\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLivechatController extends Controller
{
    public function settings()
    {
        return view('chat::admin.manage-livechat');
    }

    public function updateSettings(Request $request){
        $pusher = $request->validate([
            "PUSHER_APP_ID" => "required",
            "PUSHER_APP_KEY" => "required",
            "PUSHER_APP_SECRET" => "required",
        ]);

        setEnvValue($pusher);

        return back()->with([
            'msg' => __("Successfully updated pusher settings"),
            'type' => 'success'
        ]);
    }
}
