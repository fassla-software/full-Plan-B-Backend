<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;

class WalletController extends Controller
{
    public function wallet_history()
    {
        $user_id = auth('sanctum')->user()->id;
        $all_histories = WalletHistory::where('user_id',$user_id)->latest()->paginate(10)->withQueryString();
        $wallet_balance = Wallet::where('user_id',$user_id)->first();
        $total_wallet_balance = $wallet_balance->balance;

        if($user_id){
            return response()->json([
                'histories' => $all_histories,
                'wallet_balance' => $total_wallet_balance,
            ]);
        }
        return response()->json(['msg' => __('no history found.')]);
    }

    //deposit balance to wallet
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        $all_gateway = payment_gateway_list_for_api();

        if(empty($request->selected_payment_gateway)){
            return response()->json(['msg'=> __('Please select a payment gateway before place an order')])->setStatusCode('422');
        }
        if (!in_array($request->selected_payment_gateway, $all_gateway)) {
            return response()->json(['msg'=> __('Please select a payment gateway before place an order')]);
        }

        if ($request->selected_payment_gateway === 'manual_payment') {
            $request->validate([
                'manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf'
            ]);
        }

        //deposit amount
        $user = auth('sanctum')->user();
        $user_id = $user->id;
        $total = $request->amount;
        $name = $user->first_name . ' ' . $user->last_name;
        $email = $user->email;
        $user_type = 'client';
        $payment_status = 'pending';
        $user = Wallet::where('user_id', $user_id)->first();
        if (empty($user)) {
            Wallet::create([
                'user_id' => $user_id,
                'balance' => 0,
                'status' => 1,
            ]);
        }

        $deposit = WalletHistory::create([
            'user_id' => $user_id,
            'amount' => $total,
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => $payment_status,
            'status' => 1,

        ]);
        $last_deposit_id = $deposit->id;
        $deposit_details = WalletHistory::select('id','user_id','payment_gateway','payment_status','amount','status')->where('id', $last_deposit_id)->first();
        $title = __('Deposit To Wallet');
        $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'), $last_deposit_id, $email, $name);

        if ($request->selected_payment_gateway === 'manual_payment') {
            if ($request->hasFile('manual_payment_image')) {
                $manual_payment_image = $request->manual_payment_image;
                $img_ext = $manual_payment_image->extension();

                $manual_payment_image_name = 'manual_attachment_' . time() . '.' . $img_ext;
                if (in_array($img_ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
                    $manual_image_path = 'assets/uploads/manual-payment/';
                    $manual_payment_image->move($manual_image_path, $manual_payment_image_name);
                    WalletHistory::where('id', $last_deposit_id)->update([
                        'manual_payment_image' => $manual_payment_image_name
                    ]);
                } else {
                    return response()->json([
                        'msg'=> __('Image type not supported'),
                    ])->setStatusCode(422);
                }
            }

            try {
                $message_body = __('Hello a') . ' ' . $user_type . __('just deposit to his wallet. Please check and confirm') . '</br>' . '<span class="verify-code">' . __('Deposit ID: ') . $last_deposit_id . '</span>';
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => $message_body
                ]));
                Mail::to($email)->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => __('Manual deposit success. Your wallet will credited after admin approval') . '</br>' . '<span class="verify-code">' . __('Deposit ID: ') . $last_deposit_id . '</span>'
                ]));
            } catch (\Exception $e) {}

            return response()->json([
                'deposit_details' => $deposit_details,
                'msg'=> __('Manual deposit success. Your wallet will credited after admin approval'),
            ]);

        }else{
            try {
                $message_body = __('Hello a') . ' ' . $user_type . __('just deposit to his wallet. Please check and confirm') . '</br>' . '<span class="verify-code">' . __('Deposit ID: ') . $last_deposit_id . '</span>';
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => $message_body
                ]));
                Mail::to($email)->send(new BasicMail([
                    'subject' => __('Deposit Confirmation'),
                    'message' => __('Manual deposit success. Your wallet will credited after admin approval') . '</br>' . '<span class="verify-code">' . __('Deposit ID: ') . $last_deposit_id . '</span>'
                ]));
            } catch (\Exception $e) {
                //
            }
            return response()->json([
                'deposit_details' => $deposit_details,
                'msg'=> __('Your wallet credited successfully'),
            ]);

        }
    }

    //payment update
    public function payment_update(Request $request)
    {
        $request->validate([
            'wallet_history_id' => 'required',
            'status' => 'required'
        ]);

        $user_id = auth('sanctum')->user()->id;
        $wallet_history = WalletHistory::where('id',$request->wallet_history_id)->where('user_id',$user_id)->first();
        $last_deposit_id = $wallet_history?->id;

        if (!empty($wallet_history) && $wallet_history->payment_status == 'pending' && $request->status == 1) {
            $client = User::select(['id', 'first_name', 'last_name', 'email'])->where('id', $user_id)->first();

            $data_to_hash = $client->email;
            $ctx = hash_init('sha256', HASH_HMAC, 'apiwalletkey');
            hash_update($ctx, $data_to_hash);
            $secret_key = hash_final($ctx);

            if($request->secret_key == $secret_key){

                $deposit_details = WalletHistory::find($last_deposit_id);
                $wallet_details = Wallet::where('user_id',$deposit_details->user_id)->first();
                Wallet::where('user_id', $deposit_details->user_id)
                    ->update([
                        'balance' => $wallet_details->balance + $deposit_details->amount,
                        'remaining_balance' => $wallet_details->remaining_balance + $deposit_details->amount,
                    ]);
                WalletHistory::where('id', $last_deposit_id)->update([
                    'payment_status' => 'complete',
                    'status' => 1,
                ]);

                AdminNotification::create([
                    'identity'=>$last_deposit_id,
                    'user_id'=>$deposit_details->user_id,
                    'type'=>__('Deposit Amount'),
                    'message'=>__('User wallet deposit'),
                ]);
            }
            else
            {
                return response()->json([
                    'msg' => __('Key does not match')
                ])->setStatusCode(422);
            }
        }
        else
        {
            return response()->json([
                'msg' => __('Wallet history id not found')
            ]);
        }

        return response()->json([
            'status' => __('success'),
            'msg' => __('Deposit Status Updated Successfully')
        ]);
    }
}
