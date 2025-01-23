<?php

namespace App\Http\Services\Backend;

class TransactionService
{
    //global commission settings
   public function commission_settings($request)
   {
       if($request->isMethod('post')){
           $request->validate(['admin_commission_type' => 'required','admin_commission_charge' => 'required|numeric|gt:0']);
           $all_fields = ['admin_commission_type','admin_commission_charge'];

           foreach ($all_fields as $field) {
               update_static_option($field, $request->$field);
           }
           toastr_success(__('Commission Settings Updated Successfully.'));
           return back();
       }
   }


    //transaction fee settings
    public function transaction_fee_settings($request)
    {
        if($request->isMethod('post')){
            $request->validate(['transaction_fee_type' => 'required','transaction_fee_charge' => 'required|numeric|min:0']);
            $all_fields = ['transaction_fee_type','transaction_fee_charge'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Transaction Fee Settings Updated Successfully.'));
            return back();
        }
    }

    //withdraw fee settings
    public function withdraw_fee_settings($request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'withdraw_fee' => 'required|numeric|min:0',
                'withdraw_fee_type' => 'required',
            ]);
            $all_fields = ['withdraw_fee','withdraw_fee_type'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Withdraw Fee Settings Updated Successfully.'));
            return back();
        }
    }
}
