<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Services\Backend\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //global commission settings
    public function commission_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new TransactionService())->commission_settings($request);
        }
        return view('backend.pages.transaction.admin-commission-settings');
    }

    //transaction fee settings
    public function transaction_fee_settings(Request $request)
    {
        if($request->isMethod('post')) {
            return (new TransactionService())->transaction_fee_settings($request);
        }
        return view('backend.pages.transaction.transaction-fee-settings');
    }

    //withdraw fee settings
    public function withdraw_fee_settings(Request $request)
    {
        if($request->isMethod('post')) {
            return (new TransactionService())->withdraw_fee_settings($request);
        }
        return view('backend.pages.transaction.withdraw-fee-settings');
    }



}
