<?php

namespace Modules\Subscription\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\Entities\UserSubscription;

class ClientSubscriptionController extends Controller
{
    public function all_subscription()
    {
        $user_id = auth()->user()->id;
        $all_subscriptions = UserSubscription::with('subscription:id,subscription_type_id')->latest()->where('user_id',$user_id)->paginate(10);
        $total_limit = UserSubscription::where('user_id',$user_id)->where('payment_status','complete')->whereDate('expire_date', '>', Carbon::now())->sum('limit');
        return view('subscription::frontend.client.subscription.subscription',compact('all_subscriptions','total_limit'));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $user_id = Auth::guard('web')->user()->id;
            $all_subscriptions = $request->search_tring == ''
                ? UserSubscription::where('user_id',$user_id)->latest()->paginate(2)
                : UserSubscription::where('user_id',$user_id)->where('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
            return view('subscription::frontend.client.subscription.search-result', compact('all_subscriptions'))->render();
        }
    }

    // search category
    public function search_history(Request $request)
    {
        $all_subscriptions = UserSubscription::where('user_id',Auth::guard('web')->user()->id)->where('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_subscriptions->total() >= 1 ? view('subscription::frontend.client.subscription.search-result', compact('all_subscriptions'))->render() : response()->json(['status'=>__('nothing')]);
    }
}
