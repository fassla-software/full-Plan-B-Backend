<?php

namespace Modules\Subscription\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Entities\Subscription;

class SubscriptionSettingsController extends Controller
{
    public function limit_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['limit_settings' => 'required|numeric|gt:0']);

            $all_fields = [
                'limit_settings',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Limit Settings Updated Successfully.'));
        }
        return view('subscription::backend.settings.limit-settings');
    }

    public function free_subscription_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['register_subscription' => 'required|numeric|gt:0']);

            $all_fields = [
                'register_subscription',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Register Subscription Updated Successfully.'));
        }

        $subscriptions = Subscription::with('subscription_type')->where('status',1)->get();
        return view('subscription::backend.settings.register-subscription-settings',compact('subscriptions'));
    }

    public function subscription_enable_disable(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['subscription_enable_disable' => 'required']);

            $all_fields = [
                'subscription_enable_disable',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Subscription Settings Updated Successfully.'));
        }
        return view('subscription::backend.settings.subscription-enable-disable');
    }

}
