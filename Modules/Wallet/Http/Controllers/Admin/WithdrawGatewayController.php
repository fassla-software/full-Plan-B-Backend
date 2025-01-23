<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WithdrawGateway;
use Modules\Wallet\Entities\WithdrawRequest;
use Modules\Wallet\Http\Requests\StoreGatewayRequest;
use File;

class WithdrawGatewayController extends Controller
{
    public function gateway_settings()
    {
        $gateways  = WithdrawGateway::latest()->get();
        return view('wallet::admin.withdraw.gateways',compact('gateways'));
    }

    public function gateway_create(StoreGatewayRequest $request){
        $data = WithdrawGateway::create($request->validated());
        return back()->with(["status" => (bool)$data, "msg" => $data ? toastr_success( __("Payment Gateway Created Successfully.")) : toastr_warning(__("Failed to create payment gateway try again."))]);
    }

    public function gateway_update(StoreGatewayRequest $request){
        $data = $request->validated();

        $id = $data["id"];
        unset($data["id"]);

        $data = WithdrawGateway::where("id", $id)->update($data);

        return back()->with(["status" => (bool)$data, "msg" => $data ? toastr_success(__("Payment Gateway Updated Successfully.")) : toastr_warning(__("Failed to update payment gateway try again."))]);
    }

    public function delete_gateway($id){
        WithdrawGateway::where('id', $id)->delete();
        return back()->with(toastr_success(__("Payment Gateway Deleted Successfully.")));
    }

    public function change_status($id){
        $gateway = WithdrawGateway::findOrFail($id);
        $gateway->status == 1 ? $status = 2 : $status = 1;
        WithdrawGateway::where('id', $id)->update(['status' => $status]);
        return back()->with(toastr_success(__("Status Successfully Changed.")));
    }

    //withdraw amount settings
    public function withdraw_settings(Request $request)
    {
        $request->validate([
            'minimum_withdraw_amount'=>'numeric|gt:0',
            'maximum_withdraw_amount'=>'numeric|gt:0',
        ],
            [
                'minimum_withdraw_amount.numeric'=>'Please enter only numeric value.',
                'maximum_withdraw_amount.numeric'=>'Please enter only numeric value.'
            ]);
        if($request->isMethod('post')){
            $fields = ['minimum_withdraw_amount','maximum_withdraw_amount'];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Update Success'));
            return back();
        }
        return view('wallet::admin.wallet.withdraw-settings');
    }

    public function withdraw_request()
    {
        $all_request  = WithdrawRequest::whereHas('user')->latest()->paginate(10);
        return view('wallet::admin.withdraw.requests',compact('all_request'));
    }

    public function withdraw_request_update(Request $request)
    {
        $request->validate([
            'status' => 'required'
        ]);
        $withdraw_request = WithdrawRequest::where('id',$request->request_id)->first();
        $user_wallet_balance = Wallet::where('user_id',$withdraw_request->user_id)->first();


        $deleteOldImage =  'assets/uploads/withdraw-request/'.$withdraw_request->image;
        if($image = $request->file('image')){
            if(file_exists($deleteOldImage)){
                File::delete($deleteOldImage);
            }
            $image_original_name = $request->image->getClientOriginalName();
            $image_name = $image_original_name.'-'.time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('assets/uploads/withdraw-request', $image_name);
        }else{
            $image_name = $withdraw_request->image;
        }

        WithdrawRequest::where('id',$request->request_id)->update([
            'status' => $request->status,
            'note' => $request->note,
            'image' => $image_name
        ]);

        if($request->status == 3){
            Wallet::where('user_id',$withdraw_request->user_id)->update([
                'balance' => $user_wallet_balance->balance + $withdraw_request->amount + get_static_option('withdraw_fee'),
                'remaining_balance' => $user_wallet_balance->remaining_balance + $withdraw_request->amount + get_static_option('withdraw_fee'),
            ]);
        }

        if($request->status == 1){
            $status_text = __('pending');
        }
        if($request->status == 2){
            $status_text = __('complete');
        }
        if($request->status == 3){
            $status_text = __('cancel');
        }
        if($request->status == 4){
            $status_text = __('processing');
        }

        freelancer_notification($request->request_id,$withdraw_request->user_id,'Withdraw', __('Your withdraw request status changed to') .' '. $status_text);
        return back()->with(toastr_success(__('Status Successfully Updated')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_request = WithdrawRequest::latest()->paginate(10);
            return view('wallet::admin.withdraw.search-result', compact('all_request'))->render();
        }
    }


    //client withdraw
    public function enable_disable(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['client_withdraw_enable_disable' => 'required']);
            $all_fields = ['client_withdraw_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Client Withdraw Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('wallet::admin.withdraw.client-withdraw-enable-disable');
    }
}
