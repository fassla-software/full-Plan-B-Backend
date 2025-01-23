<?php

namespace Modules\Subscription\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Entities\SubscriptionType;

class FrontendSubscriptionController extends Controller
{
    public function subscriptions()
    {
        $subscription_types = SubscriptionType::with('subscriptions')->whereHas('subscriptions')->select('id','type','validity')->get();
        $subscriptions = Subscription::with(['features','subscription_type'])->latest()->select(['id','subscription_type_id','title','logo','price','limit'])->where('status',1)->paginate(18);
        return view('subscription::frontend.subscriptions.subscriptions',compact(['subscription_types','subscriptions']));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $subscriptions = Subscription::with(['features','subscription_type'])->latest()->select(['id','subscription_type_id','title','logo','price','limit'])->paginate(12);
            return view('subscription::frontend.subscriptions.search-result', compact('subscriptions'))->render();
        }
    }

    // subscription filter
    public function filter_subscriptions(Request $request)
    {
        $type_id = $request->type_id;
        if ($type_id == 'all') {
            $subscriptions = Subscription::with(['features','subscription_type'])
                ->latest()
                ->select(['id','subscription_type_id','title','logo','price','limit'])
                ->where('status',1)
                ->paginate(18);
        }else {
            $subscriptions = Subscription::with(['features','subscription_type'])
                ->latest()->select(['id','subscription_type_id','title','logo','price','limit'])
                ->where('subscription_type_id',$type_id)
                ->where('status',1)
                ->get();
        }
        return $subscriptions->count() >= 1 ? view('subscription::frontend.subscriptions.search-result', compact(['subscriptions','type_id']))->render() :  response()->json(['status' => 'nothing']);
    }

    //user login
    public function user_login(Request $request)
    {
        $email_or_username = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6'
        ],
        [
            'username.required' => sprintf(__('%s is required'),$email_or_username),
            'password.required' => __('password is required')
        ]);

        return Auth::guard('web')->attempt([$email_or_username => $request->username, 'password' => $request->password])
        ? response()->json(['status' => 'success','balance' => Auth::user()->user_wallet->balance ?? 0 ])
        : response()->json(['msg' => sprintf(__('Your %s or Password Is Wrong !!'),$email_or_username),'status' => 'failed']);
    }
}
