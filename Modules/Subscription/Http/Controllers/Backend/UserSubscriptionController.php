<?php

namespace Modules\Subscription\Http\Controllers\Backend;

use App\Mail\BasicMail;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Mail;
use Modules\Subscription\Entities\UserSubscription;

class UserSubscriptionController extends Controller
{
    //user subscriptions
    public function all_subscription()
    {
        $all_subscriptions = UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id','user:id,user_type,email,first_name')->latest()->paginate(10);
        $active_subscription = UserSubscription::whereHas('user')->where('status',1)->count();
        $inactive_subscription = UserSubscription::whereHas('user')->where('status',0)->count();
        $manual_subscription = UserSubscription::whereHas('user')->where('payment_gateway','manual_payment')->count();
        $route = route("admin.user.subscription.paginate.data");

        return view('subscription::backend.user-subscription.all-subscription',compact(['all_subscriptions','active_subscription','inactive_subscription','manual_subscription','route']));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_subscriptions = $request->string_search == ''
                ? UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest()->paginate(10)
                : UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest()->$this->query__($request);

            $route = route("admin.user.subscription.paginate.data");

            return view('subscription::backend.user-subscription.search-result', compact('all_subscriptions', 'route'))->render();
        }
    }

    // search string
    public function search_subscription(Request $request)
    {
        $query = UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest();
        if($request->filter_val != ''){
            if($request->filter_val == 1){
                $query->where('status',1);
            }
            if($request->filter_val == 0){
                $query->where('status',0);
            }
            if($request->filter_val == 'manual_payment'){
                $query->where('payment_gateway','manual_payment');
            }
        }

        $all_subscriptions = $query->where(function($q) use($request){
            $q->where('id', 'LIKE', "%". strip_tags($request->string_search) ."%")
                    ->orWhere('user_id', 'LIKE', "%". strip_tags($request->string_search) ."%")
                    ->orWhere('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")
                    ->orWhere('expire_date', 'LIKE', "%". strip_tags($request->string_search) ."%");
            })->paginate(10);

        $route = route("admin.user.subscription.search");

        return $all_subscriptions->total() >= 1 ? view('subscription::backend.user-subscription.search-result', compact('all_subscriptions', 'route'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //change status
    public function change_status($id)
    {
        $subscription = UserSubscription::find($id);
        $user_firstname = $subscription->user?->first_name ?? '';
        $user_email = $subscription->user?->email ?? '';
        $status = $subscription->status == 1 ? 0 : 1;
        if($status == 0){
            try {
                $message = get_static_option('subscription_inactive_message') ?? __('Your subscription status changed from active to inactive.');
                $message = str_replace(["@name","@subscription_id"],[$user_firstname, $id], $message);
                Mail::to($user_email)->send(new BasicMail([
                    'subject' => get_static_option('subscription_inactive_subject') ?? __('Subscription Inactive'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }else{
            try {
                $message = get_static_option('subscription_active_message') ?? __('Your subscription status changed from inactive to active.');
                $message = str_replace(["@name","@subscription_id"],[$user_firstname, $id], $message);
                Mail::to($user_email)->send(new BasicMail([
                    'subject' => get_static_option('subscription_active_subject') ?? __('Subscription Active'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }
        UserSubscription::where('id',$id)->update(['status'=>$status]);
        return back()->with(toastr_success(__('Status successfully changed')));
    }

    //active subscription
    public function active_subscriptions(Request $request)
    {
        $all_subscriptions = $request->string_search == ''
        ? UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->where('status',1)->paginate(10)
        : UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest()->where('status',1)->$this->query__($request);

        $route = route("admin.user.subscription.active");

        return $all_subscriptions->total() >= 1 ? view('subscription::backend.user-subscription.search-result', compact('all_subscriptions', 'route'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //inactive subscription
    public function inactive_subscriptions(Request $request)
    {
        $all_subscriptions = $request->string_search == ''
            ? UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->where('status',0)->paginate(10)
            : UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest()->where('status',0)->$this->query__($request);
        $route = route("admin.user.subscription.active");

        return $all_subscriptions->total() >= 1 ? view('subscription::backend.user-subscription.search-result', compact('all_subscriptions', 'route'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //manual subscription
    public function manual_subscriptions(Request $request)
    {
        $all_subscriptions = $request->string_search == ''
            ? UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->where('payment_gateway','manual_payment')->paginate(10)
            : UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id')->latest()->where('payment_gateway','manual_payment')->$this->query__($request);
        $route = route("admin.user.subscription.active");

        return $all_subscriptions->total() >= 1 ? view('subscription::backend.user-subscription.search-result', compact('all_subscriptions', 'route'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //read unread
    public function read_unread($id)
    {
        AdminNotification::where('identity',$id)->update(['is_read'=>1]);
        return redirect()->route('admin.user.subscription.all');
    }

    //update manual payment
     public function update_manual_payment(Request $request)
     {
         $subscription_details = UserSubscription::select('id','payment_status')->where('id',$request->subscription_id)->first();
         $payment_status = $subscription_details->payment_status == 'pending' ? 'complete' : 'pending';
         UserSubscription::where('id',$request->subscription_id)->update(['payment_status'=>$payment_status,'status'=> 1]);

         //Send manual subscription payment complete email to user
         try {
             $message = get_static_option('manual_subscription_complete_message') ?? __('Your manual subscription payment successfully completed.');
             $message = str_replace(["@name","@subscription_id"],[$request->user_firstname, $request->subscription_id], $message);
             Mail::to($request->user_email)->send(new BasicMail([
                 'subject' => get_static_option('manual_subscription_complete_subject') ?? __('Manual subscription payment complete email'),
                 'message' => $message
             ]));
         } catch (\Exception $e) {}

         //Send manual subscription payment complete email to admin
         try {
             $message = get_static_option('manual_subscription_complete_message_to_admin') ?? __('A manual subscription payment successfully completed.');
             $message = str_replace(["@subscription_id"],[$request->subscription_id], $message);
             Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                 'subject' => get_static_option('manual_subscription_complete_subject_to_admin') ?? __('Manual subscription payment complete'),
                 'message' => $message
             ]));
         } catch (\Exception $e) {}

         return redirect()->back()->with(toastr_success(__('Payment Successfully Changed')));
     }
    private function query__($request)
    {
        UserSubscription::where(function($query) use($request){
        $query->where('id', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->orWhere('user_id', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->orWhere('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->orWhere('expire_date', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->paginate(10);
    }
}
