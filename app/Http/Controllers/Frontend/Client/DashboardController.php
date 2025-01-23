<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Order;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Wallet\Entities\Wallet;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user_id = Auth::guard('web')->user()->id;
        $wallet_balance = Wallet::where('user_id',$user_id)->first();
        $total_wallet_balance = $wallet_balance->balance ?? 0;
        $total_jobs = JobPost::where('user_id',$user_id)->count();

        if(Auth::guard('web')->user()->user_type == 2 && Session::get('user_role') == 'client'){
            $complete_order = Order::where('status',3)->whereHas('user')->where('user_id',$user_id)->where('order_type','client_order')->count();
            $active_order = Order::where('status',1)->whereHas('user')->where('user_id',$user_id)->where('order_type','client_order')->count();

            if(get_static_option('project_enable_disable') != 'disable'){
                $latest_orders = Order::whereHas('freelancer')
                    ->where(function ($query) use ($user_id) {
                        $query->where(function ($query) {
                            $query->where('payment_status', 'complete');
                        })->orWhere(function ($query) {
                            $query->where("payment_gateway", "manual_payment")
                                ->whereIn("payment_status", ["pending", "complete"]);
                        });
                    })
                    ->where('user_id', $user_id)
                    ->latest()
                    ->take(5)
                    ->get();
            }else{
                $latest_orders = Order::where('user_id', $user_id)
                    ->where('is_project_job', '!=', 'project')
                    ->where(function ($query) {
                        $query->whereHas('freelancer', function ($q) {
                            $q->where('payment_status', 'complete');
                        })
                            ->orWhere(function ($query) {
                                $query->where('payment_gateway', 'manual_payment')
                                    ->whereIn('payment_status', ['pending', 'complete']);
                            });
                    })
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }else{
            $complete_order = Order::where('status',3)->whereHas('freelancer')->where('user_id',$user_id)->count();
            $active_order = Order::where('status',1)->whereHas('freelancer')->where('user_id',$user_id)->count();

            if(get_static_option('project_enable_disable') != 'disable'){
                $latest_orders = Order::whereHas('freelancer')
                    ->where(function ($query) use ($user_id) {
                        $query->where(function ($query) {
                            $query->where('payment_status', 'complete');
                        })->orWhere(function ($query) {
                            $query->where("payment_gateway", "manual_payment")
                                ->whereIn("payment_status", ["pending", "complete"]);
                        });
                    })
                    ->where('user_id', $user_id)
                    ->latest()
                    ->take(5)
                    ->get();
            }else{
                $latest_orders = Order::where('user_id', $user_id)
                    ->where('is_project_job', '!=', 'project')
                    ->where(function ($query) {
                        $query->whereHas('freelancer', function ($q) {
                            $q->where('payment_status', 'complete');
                        })
                            ->orWhere(function ($query) {
                                $query->where('payment_gateway', 'manual_payment')
                                    ->whereIn('payment_status', ['pending', 'complete']);
                            });
                    })
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

            $my_jobs = JobPost::select('id','title','slug')->where('user_id',$user_id)->latest()->take(5)->get();
            return view('frontend.user.client.dashboard.dashboard',compact(['total_wallet_balance','total_jobs','complete_order','active_order','latest_orders','my_jobs']));
    }

    public function switch_profile(Request $request)
    {
        $request->validate([
            'role' => 'required|in:client,freelancer',
        ]);
        Session::put('user_role', $request->role);
        return response()->json([
            'status' => 'success',
            'user_role' => Session::get('user_role')
        ]);
    }
}
