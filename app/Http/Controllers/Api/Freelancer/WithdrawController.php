<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WithdrawGateway;
use Modules\Wallet\Entities\WithdrawRequest;
use Modules\Wallet\Http\Requests\FreelancerHandleWithdrawRequestAPI;

class WithdrawController extends Controller
{
    //withdraw settings
    public function withdraw_settings()
    {
        $user_id = auth('sanctum')->user()->id;
        $minimum_withdraw_amount = get_static_option('minimum_withdraw_amount') ?? 0;
        $maximum_withdraw_amount = get_static_option('maximum_withdraw_amount') ?? 0;
        $withdraw_fee_type = get_static_option('withdraw_fee_type') ?? '';
        $withdraw_fee = get_static_option('withdraw_fee') ?? 0;

        $user_current_balance  = Wallet::select('balance')->where('user_id', $user_id)->first();
        $withdraw_gateways = WithdrawGateway::select('id','name','field')->where('status',1)->get()->transform(function ($item){
            $item->field = unserialize($item->field);
            return $item;
        });

        return response()->json([
            'withdraw_gateways' => $withdraw_gateways,
            'user_current_balance' => $user_current_balance,
            'minimum_withdraw_amount' => $minimum_withdraw_amount,
            'maximum_withdraw_amount' => $maximum_withdraw_amount,
            'withdraw_fee_type' => $withdraw_fee_type,
            'withdraw_fee' => $withdraw_fee,
        ]);

    }

    //withdraw request
    public function withdraw_request(FreelancerHandleWithdrawRequestAPI $request)
    {
        $data = $request->validated();

        if(strlen($request->gateway_fields) <=10){
            return response()->json([
                'msg'=> __("Gateway field is required")
            ])->setStatusCode(422);
        }
        $wallet = Wallet::where("user_id", $data["user_id"])->first();

        //find user for withdraw freeze check
        $find_user_for_withdraw_freeze = User::find($wallet->user_id);
        if($find_user_for_withdraw_freeze->freeze_withdraw == 'freeze'){
            return response()->json([
                'msg' => __('Your withdraw request has been freeze. Please contact your administrator.')
            ])->setStatusCode(422);
        }

        $withdraw_fee = 0;
        if(get_static_option('withdraw_fee_type') == 'percentage'){
            $withdraw_fee = ($data["amount"]*get_static_option('withdraw_fee'))/100;
        }else{
            $withdraw_fee = get_static_option('withdraw_fee');
        }

        $amount = $data["amount"]+$withdraw_fee;

        if($amount < get_static_option('minimum_withdraw_amount') || $amount > get_static_option('maximum_withdraw_amount')){
            return response()->json([
                'msg'=> __("Please enter a valid amount between ".float_amount_with_currency_symbol(get_static_option('minimum_withdraw_amount')). '-' .float_amount_with_currency_symbol(get_static_option('maximum_withdraw_amount')))
            ])->setStatusCode(422);
        }

        if($wallet->balance >= $data["amount"]){
            $withdraw = WithdrawRequest::create($data);
            Wallet::where('user_id',$withdraw->user_id)->update([
                'balance'=> $wallet->balance - $amount,
                'remaining_balance'=> $wallet->balance - $amount,
                'withdraw_amount'=> $wallet->withdraw_amount + $amount
            ]);

            notificationToAdmin($withdraw->id, $withdraw->user_id, 'Withdraw', 'New withdraw request');
            return response()->json([
                'msg' => __("Successfully sent your request"),
            ]);
        }
        return response()->json([
            'msg' => __('Your requested amount is greater than your wallet balance')
        ])->setStatusCode(422);
    }

    //withdraw history
    public function withdraw_history()
    {
        $query = WithdrawRequest::where('user_id', auth('sanctum')->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $all_request = $query
            ->through(function ($item) {
                $item->field = unserialize($item->gateway_fields);
                return $item;
            });

        if($all_request){
            return response()->json([
                'histories' => $all_request,
                'image_path' => asset('assets/uploads/withdraw-request/'),
            ]);
        }
        return response()->json(['msg' => __('No history found.')]);
    }
}
