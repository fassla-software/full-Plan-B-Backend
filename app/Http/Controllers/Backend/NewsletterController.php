<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\NewsLetterForEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\NewsLetter\Entities\NewsLetter;

class NewsletterController extends Controller
{
    public function all_newsletter()
    {
        $all_newsletter = NewsLetter::latest()->paginate(10);
        return view ('backend.pages.newsletter.all-newsletter',compact('all_newsletter'));
    }

    public function add_email(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:newsletters',
        ]);

        NewsLetter::create(['email'=>$request->email,'verified'=>'yes']);
        return back()->with(toastr_success(__('Email Successfully Added')));
    }

    public function send_email(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'subject' => 'required|max:150',
            'message' => 'required',
        ]);

        try {
            Mail::to($request->email)->send(new BasicMail([
                'subject' => $request->subject,
                'message' => $request->message
            ]));

        }catch (\Exception $e){}

        return back()->with(toastr_success(__('Email Successfully Send')));
    }

    public function send_email_to_all(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'subject' => 'required|max:150',
                'message' => 'required',
            ]);

            $all_subscriber = Newsletter::where('verified','yes')->get();
            foreach ($all_subscriber as $subscriber){
                try {
                    Mail::to($subscriber->email)->send(new BasicMail([
                        'subject' => $request->subject,
                        'message' => $request->message
                    ]));

                }catch (\Exception $e){}
            }
            return back()->with(toastr_success(__('Email Successfully Send')));
        }
        return view ('backend.pages.newsletter.send-mail-to-all');
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_newsletter = NewsLetter::latest()->paginate(10);
            return view('backend.pages.newsletter.search-result',compact('all_newsletter'))->render();
        }
    }

    public function verify_email_send($id){
        $subscriber_details = Newsletter::findOrFail($id);
        $token = $subscriber_details->token ?? Str::random(32);
        if (empty($subscriber_details->token)){
            NewsLetter::where(['email'=>$subscriber_details->email])->update(['token'=>$token]);
        }
        $subject = __('Verify your email');
        $message = __('Verify your email to get all news from ') . get_static_option('site_title') . '.<br><br><a href="' . route('newsletter.subscriber.verify', $token) . '" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">' . __('Verify Email') . '</a>';

        try {
            Mail::to($subscriber_details->email)->send(new BasicMail([
                'subject' => $subject,
                'message' => $message
            ]));

        }catch (\Exception $e){}

        return back()->with(toastr_success(__('Verify email successfully send')));
    }

    public function delete_email($id)
    {
        NewsLetter::find($id)->delete();
        return back()->with(toastr_success(__('Subscriber Successfully Deleted')));
    }

}
