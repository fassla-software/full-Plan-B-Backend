<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use App\Models\AdminNotification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;

class WalletController extends Controller
{
    //deposit amount settings
    public function deposit_settings(Request $request)
    {
        $request->validate([
                'deposit_amount_limitation_for_user'=>'numeric|gt:0|max:500000000',
           ],
            [
                'deposit_amount_limitation_for_user.numeric'=>'Please enter only numeric value.'
        ]);
        if($request->isMethod('post')){
            $fields = ['deposit_amount_limitation_for_user'];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('wallet::admin.wallet.deposit-settings');
    }

    //display wallet history
    public function wallet_history()
    {
        $all_histories = WalletHistory::whereHas('user')->latest()->paginate(10);
        return view('wallet::admin.wallet.wallet-history',compact('all_histories'));
    }

    public function history_details($id)
    {
        $history_details = WalletHistory::whereHas('user')->where('id',$id)->first();
        AdminNotification::where('identity',$id)->update(['is_read'=>1]);

        return !empty($history_details) ? view('wallet::admin.wallet.history-details',compact('history_details')) : back();
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_histories = WalletHistory::whereHas('user')->latest()->paginate(10);
            return view('wallet::admin.wallet.search-result', compact('all_histories'))->render();
        }
    }

    // search category
    public function search_history(Request $request)
    {
        $all_histories = WalletHistory::whereHas('user')->where('created_at', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_histories->total() >= 1){
            return view('wallet::admin.wallet.search-result', compact('all_histories'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }

    // change history status
    public function change_status($id)
    {
        $history = WalletHistory::select('user_id','payment_gateway','payment_status','amount')->where('id',$id)->first();

        if($history->payment_status == 'pending'){
            $user_wallet = Wallet::where('user_id',$history->user_id)->first();
            WalletHistory::where('id',$id)->update(['payment_status'=>'complete']);
            Wallet::where('user_id',$user_wallet->user_id)->update(['balance'=> $user_wallet->balance + $history->amount]);
            return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
        }else{
            return back();
        }

    }
}
