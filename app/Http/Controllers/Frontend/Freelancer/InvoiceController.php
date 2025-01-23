<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function generate_invoice($order_id)
    {
        $order = Order::with(['user','freelancer'])
            ->where('id', $order_id)->where('freelancer_id',Auth::guard('web')->user()->id)
            ->where('status',3)
            ->firstOrFail();

        if($order){
            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Invoice generate','Freelancer');
            }
            $pdf = Pdf::loadView('frontend.user.freelancer.order.order-invoice',compact('order'));
            return $pdf->stream('billing-invoice');
        }else{
            return back();
        }

    }
}
