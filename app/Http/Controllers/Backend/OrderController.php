<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\OrderMilestone;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Wallet\Entities\Wallet;

class OrderController extends Controller
{
    // all orders
    public function all_orders()
    {
        $is_payment_valid = get_static_option('payment_failed_order_enable_disable');

        if($is_payment_valid == 'disable'){}

        $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->paginate(10);
        $pending_orders =  Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',0)->paginate(10);
        $active_orders =   Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',1)->paginate(10);
        $deliver_orders =  Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',2)->paginate(10);
        $complete_orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',3)->paginate(10);
        $cancel_orders =   Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',4)->paginate(10);
        $decline_orders =   Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',5)->paginate(10);
        $hold_orders =     Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',7)->paginate(10);
        return view('backend.pages.orders.all-order',compact([
            'orders',
            'pending_orders',
            'active_orders',
            'deliver_orders',
            'complete_orders',
            'cancel_orders',
            'decline_orders',
            'hold_orders'
        ]));
    }

    public function order_sort_by_status(Request $request)
    {
        if($request->ajax()){
            $request->status == 'queue' ? $status = 0 : $status = $request->status;
            $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',$status)->paginate(10);
            return view('backend.pages.orders.search-result',compact('orders'))->render();
        }
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search) && empty($request->status)){
                $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->paginate(10);
            }else{
                if($request->status){
                    $request->status == 'queue' ? $status = 0 : $status = $request->status;
                    $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',$status)->paginate(10);
                }
                if($request->string_search){
                    $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where(function($q) use($request){
                        $q->where('id', strip_tags($request->string_search))
                            ->orWhere('is_project_job', strip_tags($request->string_search))
                            ->orWhereDate('created_at', strip_tags($request->string_search));
                    })->paginate(10);
                }
            }
            return view('backend.pages.orders.search-result', compact('orders'))->render();
        }
    }

    // search order
    public function search_order(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search)){
                $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->paginate(10);
                return view('backend.pages.orders.search-result', compact('orders'))->render();
            }else{
                if($request->status){
                    $request->status == 'queue' ? $status = 0 : $status = $request->status;
                    $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where('status',$status)->where(function($q) use($request){
                        $q->orWhere('id', strip_tags($request->string_search))
                            ->orWhere('is_project_job', strip_tags($request->string_search))
                            ->orWhereDate('created_at', strip_tags($request->string_search));
                    })->paginate(10);

                }else{
                    $orders = Order::whereHas('user')->wherehas('freelancer')->latest()->where(function($q) use($request){
                        $q->where('id', strip_tags($request->string_search))
                            ->orWhere('is_project_job', strip_tags($request->string_search))
                            ->orWhereDate('created_at', strip_tags($request->string_search));
                    })->paginate(10);
                }
                return $orders->total() >= 1
                    ? view('backend.pages.orders.search-result', compact('orders'))->render()
                    : response()->json(['status'=>__('nothing')]);
            }
        }

    }

    // order details
    public function order_details($id)
    {
        $order_details = Order::whereHas('user')->wherehas('freelancer')->with(['rating','order_submit_history','user:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,is_suspend','freelancer:id,image,first_name,last_name,email,phone,country_id,state_id,city_id,username,is_suspend','order_mile_stones'])
            ->where('id',$id)
            ->first();
        return !empty($order_details) ? view('backend.pages.orders.order-details',compact('order_details')) : back();
    }

    //hold order
    public function hold_order($id)
    {
        $order = Order::with(['user','freelancer'])->where('id',$id)->first();
        Order::where('id',$id)->update(['status_before_hold'=>$order->status,'status'=>7]);

        //notification to freelancer and client
        freelancer_notification($id, $order->freelancer_id,'Order',__('Order Hold'));
        client_notification($id, $order->user_id,'Order',__('Order Hold'));
        //Email to client
        try {
            $message = get_static_option('order_hold_message') ?? __('Order Hold Message');
            $message = str_replace(["@name","@order_id"],[$order->user?->fullname,$id], $message);
            Mail::to($order->user?->email)->send(new BasicMail([
                'subject' => get_static_option('order_hold_subject') ?? __('Order Hold Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}

        //Email to freelancer
        try {
            $message = get_static_option('order_hold_message') ?? __('Order Hold Message');
            $message = str_replace(["@name","@order_id"],[$order->freelancer?->fullname,$id], $message);
            Mail::to($order->freelancer?->email)->send(new BasicMail([
                'subject' => get_static_option('order_hold_subject') ?? __('Order Hold Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}

        return back()->with(toastr_success(__('Order Successfully Hold.')));
    }

    //unhold order
    public function unhold_order($id)
    {
        $order = Order::with(['user','freelancer'])->where('id',$id)->first();
        Order::where('id',$id)->update(['status_before_hold'=>0,'status'=>$order->status_before_hold]);
        //notification to freelancer and client
        freelancer_notification($id, $order->freelancer_id, 'Order',__('Order Unhold'));
        client_notification($id, $order->user_id, 'Order',__('Order Unhold'));

        //Email to client
        try {
            $message = get_static_option('order_unhold_message') ?? __('Order Unhold Message');
            $message = str_replace(["@name","@order_id"],[$order->user?->fullname,$id], $message);
            Mail::to($order->user?->email)->send(new BasicMail([
                'subject' => get_static_option('order_unhold_subject') ?? __('Order Unhold Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}

        //Email to freelancer
        try {
            $message = get_static_option('order_unhold_message') ?? __('Order Hold Message');
            $message = str_replace(["@name","@order_id"],[$order->freelancer?->fullname,$id], $message);
            Mail::to($order->freelancer?->email)->send(new BasicMail([
                'subject' => get_static_option('order_unhold_subject') ?? __('Order Hold Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return back()->with(toastr_success(__('Order Successfully Unhold.')));
    }

    //update manual payment
    public function update_manual_payment(Request $request)
    {
        $order = Order::where('id',$request->order_id)->first();
        Order::where('id',$request->order_id)->update(['payment_status'=>'complete']);
        client_notification($request->order_id, $order->user_id, 'Order',__('Order Payment Confirm'));

        //Email to client
        try {
            $message = get_static_option('order_manual_payment_complete_message') ?? __('Order Manual Payment Complete Message');
            $message = str_replace(["@name","@order_id"],[$order->user?->fullname,$request->order_id], $message);
            Mail::to($order->user?->email)->send(new BasicMail([
                'subject' => get_static_option('order_manual_payment_complete_subject') ?? __('Order Manual Payment Complete Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return back()->with(toastr_success(__('Order Manual Payment Successfully Completed.')));
    }


    //order auto approval settings
    public function auto_approval_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['order_auto_approval' => 'required']);
            $all_fields = ['order_auto_approval'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Auto Approval Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.orders.order-auto-approval-settings');
    }

    //change order status
    public function change_status($id)
    {
        $order_details = Order::select(['id','freelancer_id','user_id','price','payment_status','status'])->where('id',$id)->first();
        //update wallet balance
        if($order_details->payment_status === 'complete' && $order_details->status == 3){
            $freelancer = Wallet::select('balance','remaining_balance')->where('user_id',$order_details->freelancer_id)->first();
            $client = Wallet::select('balance')->where('user_id',$order_details->user_id)->first();
//            $mile_stones = OrderMilestone::where('order_id',$order_details->id)->get();

            Order::where('id',$id)->update(['status'=>4]);

//            if($mile_stones > 0){
//                OrderMilestone::where('order_id',$id)->update(['status'=>4]);
//            }


            Wallet::where('user_id',$order_details->user_id)->update([
                'balance'=> $client->balance + $order_details->price
            ]);

            Wallet::where('user_id',$order_details->freelancer_id)->update([
                'balance'=> $freelancer->balance - $order_details->price,
                'remaining_balance'=> $freelancer->remaining_balance - $order_details->price
            ]);

            Report::where('client_id',$order_details->user_id)->update([
                'status'=> 1
            ]);
        }

        return back()->with(toastr_success(__('Order successfully cancel')));
    }

    // cancel & decline
    public function decline_order($id)
    {
        $order_details = Order::select(['id','freelancer_id','user_id','price','payment_status'])->where('id',$id)->first();
        //update wallet balance
        if($order_details->payment_status === 'complete'){
            $user = Wallet::select('balance')->where('user_id',$order_details->user_id)->first();
            Order::where('id',$id)->update(['status'=>5]);
            Wallet::where('user_id',$order_details->user_id)->update([
                'balance'=> $user->balance + $order_details->price
            ]);
        }

        //order decline and cancel mail to admin and client

        //client notification
        client_notification($id, $order_details->user_id,'Order',__('Order decline'));

        return back()->with(toastr_success(__('Order Successfully Decline.')));
    }

    //order auto approval settings
    public function order_enable_disable_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['payment_failed_order_enable_disable' => 'required']);
            $all_fields = ['payment_failed_order_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            if($request->payment_failed_order_enable_disable == 'disable'){
                Order::where('payment_status','pending')->where('payment_gateway','!=','manual_payment')->update(['is_valid_payment'=>'disable']);
            }
            toastr_success(__('Order Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.orders.order-create-settings');
    }

    public function order_enable_disable_description_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['order_enable_disable_description_settings' => 'required']);
            $all_fields = ['order_enable_disable_description_settings'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Order Enable Disable Description Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.orders.order-description-enable-disable-settings');
    }

    public function order_enable_disable_milestone_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['order_enable_disable_milestone_settings' => 'required']);
            $all_fields = ['order_enable_disable_milestone_settings'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Order Milestone Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.orders.order-milestone-enable-disable');
    }


}
