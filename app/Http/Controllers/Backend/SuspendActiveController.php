<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\User;
use App\Models\UserEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Wallet\Entities\Wallet;

class SuspendActiveController extends Controller
{
    //suspend user
    public function suspend(Request $request, $id)
    {
        if($request->isMethod('post')){
            //here $id is user id. it could be freelancer or client
            $user = User::where('id',$id)->first();
            User::where('id',$id)->update(['is_suspend'=>1]);

            //get pending orders only payment status complete
            $query = Order::select('id','user_id','freelancer_id','status','price')->where('status',0)->where('payment_status','complete');
            $order_balance = 0;

            if($user->user_type == 2){
                //freelancer pending order
                $pending_orders = $query->where('freelancer_id',$id)->get();
                if($pending_orders->count() > 0){
                    foreach ($pending_orders as $order){
                        Order::where('id',$order->id)->update(['status'=>5]);
                        $wallet_balance = Wallet::select('balance')->where('user_id',$order->user_id)->first();
                        if($wallet_balance){
                            //if client has wallet
                            Wallet::where('user_id',$order->user_id)->update(['balance' => ($wallet_balance->balance + $order->price)]);
                        }else{
                            //if client has not wallet
                            Wallet::create([
                                'user_id' => $order->user_id,
                                'balance' => $order->price,
                                'status' => 0,
                            ]);
                        }
                        Order::where('id',$order->id)->update(['status'=>5]);
                        //notification to client after suspend freelancer account and deposit his pending order balance to wallet
                        client_notification($order->user_id, $user->id,'Deposit',__('Your pending order price has been added to your wallet because the freelancer account has been suspended.'));
                    }
                }

            }else{
                //client pending order
                $pending_orders = $query->where('user_id',$id)->get();
                if($pending_orders->count() > 0){
                    $wallet_balance = Wallet::select('balance')->where('user_id',$id)->first();
                    foreach ($pending_orders as $order){
                        $order_balance += $order->price;
                        Order::where('id',$order->id)->update(['status'=>5]);
                    }
                    if($wallet_balance){
                        //if client has wallet
                        Wallet::where('user_id',$id)->update(['balance' => ($wallet_balance->balance + $order_balance)]);
                    }else{
                        //if client has not wallet
                        Wallet::create([
                            'user_id' => $id,
                            'balance' => $wallet_balance,
                            'status' => 0,
                        ]);
                    }
                    //notification to client after suspend freelancer account and deposit his pending order balance to wallet
                    client_notification($id, $user->id,'Deposit',__('Your pending order price has been added to your wallet because your account has been suspended.'));
                }
            }

            $user->user_type == 1 ? client_notification($id, $user->id,'Account',__('Account Suspend')) : freelancer_notification($id, $user->id,'Account',__('Account Suspend'));

            //Email to freelancer or client according to their id
            try {
                $message = get_static_option('account_suspend_message') ?? __('Account Suspend Message');
                $message = str_replace(["@name"],[$user->fullname], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('account_suspend_subject') ?? __('Account Suspend Email'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}
            return back()->with(toastr_success(__('Account Successfully Suspended.')));
        }

        //here $id is order id
        $order = Order::find($id);
        //freelancer details
        $freelancer_active_order = Order::where('status',1)->where('freelancer_id',$order->freelancer_id)->count();
        $freelancer_deliver_order = Order::where('status',2)->where('freelancer_id',$order->freelancer_id)->count();
        $freelancer_remaining_balance = UserEarning::select('remaining_balance')->where('user_id',$order->freelancer_id)->first();
        $freelancer_wallet_balance = Wallet::select('balance')->where('user_id',$order->freelancer_id)->first();
        //client details
        $client_active_order = Order::where('status',1)->where('user_id',$order->user_id)->count();
        $client_deliver_order = Order::where('status',2)->where('user_id',$order->user_id)->count();
        $client_wallet_balance = Wallet::select('balance')->where('user_id',$order->user_id)->first();
        return view('backend.pages.account.suspend',compact([
            'order',
            'freelancer_active_order',
            'freelancer_deliver_order',
            'freelancer_remaining_balance',
            'freelancer_wallet_balance',
            'client_active_order',
            'client_deliver_order',
            'client_wallet_balance'
        ]));
    }

    //unsuspend user
    public function unsuspend($id)
    {
        $user = User::find($id);
        User::where('id',$id)->update(['is_suspend'=>0]);
        $user->user_type == 1 ? client_notification($id,$user->id,'Account',__('Account Unsuspended')) : freelancer_notification($id, $user->id,'Account',__('Account Unsuspended'));
        //Email to freelancer or client according to their id
        try {
            $message = get_static_option('account_unsuspend_message') ?? __('Account Unsuspend Message');
            $message = str_replace(["@name"],[$user->fullname], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('account_unsuspend_subject') ?? __('Account Unsuspend Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return back()->with(toastr_success(__('Account Successfully Unsuspended.')));
    }
}
