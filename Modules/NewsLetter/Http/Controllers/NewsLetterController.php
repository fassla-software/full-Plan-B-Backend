<?php

namespace Modules\NewsLetter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\NewsLetter\Entities\NewsLetter;

class NewsLetterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:newsletters']);
        $subscribe = NewsLetter::create(['email' => $request->email]);
        return !empty($subscribe) ? response()->json(['status'=>'success']) : response()->json(['status'=>'failed','msg' => __('Something Went wrong')]);
    }

    public function subscriber_verify(Request $request){
        $newsletter = Newsletter::where('token',$request->token)->first();
        $title = __('Sorry');
        $description = __('your token is expired');
        if (!empty($newsletter)){
            Newsletter::where('token',$request->token)->update([
                'verified' => 'yes'
            ]);
        }
         return redirect()->route('homepage')->with(toastr_success(__('Thanks. we are really thankful to you for subscribe our newsletter')));
    }
}
