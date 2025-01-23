<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Order;
use App\Models\OrderDeclineHistory;
use App\Models\OrderDeclineWalletHistory;
use App\Models\OrderMilestone;
use App\Models\OrderSubmitHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Wallet\Entities\Wallet;

class OrderController extends Controller
{
    public function all_order()
    {
        $freelancer_id = auth('sanctum')->user()->id;

        $orders = Order::with(
            'rating:id,order_id,rating,sender_type',
            'user:id,image,first_name,last_name,load_from',
            'project:id,title',
            'job:id,title,hourly_rate'
        )
            ->where('freelancer_id',$freelancer_id)
            ->where('payment_status','complete')
            ->latest()
            ->paginate(10);
        $queue_orders =  Order::where('freelancer_id',$freelancer_id)->where('payment_status','complete')->where('status',0)->count();
        $active_orders =  Order::where('freelancer_id',$freelancer_id)->where('payment_status','complete')->where('status',1)->count();
        $complete_orders = Order::where('freelancer_id',$freelancer_id)->where('payment_status','complete')->where('status',3)->count();
        $cancel_orders = Order::where('freelancer_id',$freelancer_id)->where('payment_status','complete')->where('status',4)->count();


        if($orders){
            $orderList = $orders->getCollection()->transform(function ($order){
                if($order->is_project_job == 'project') {
                    $order->job = null;
                }
                if($order->is_project_job == 'job') {
                    $order->project = null;
                }
                if($order->is_project_job == 'offer') {
                    unset($order->project,$order->job );
                    $order->project = null;
                    $order->job = null;
                }
                return $order;
            });

            $orders = $orders->setCollection($orderList);

            if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $orders->transform(function ($order) {
                    $order->user->cloud_link = render_frontend_cloud_image_if_module_exists('profile/'.$order->user?->image, load_from: $order->user?->load_from);
                    return $order;
                });
            }

            return response()->json([
                'orders' => $orders->withQueryString(),
                'total_count' => $orders->total(),
                'queue_orders' => $queue_orders,
                'active_orders' => $active_orders,
                'complete_orders' => $complete_orders,
                'cancel_orders' => $cancel_orders,
                'image_path' => asset('assets/uploads/profile/'),
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no order found.')]);

    }

    // order details
    public function order_details($id)
    {
        $freelancer_id = auth('sanctum')->user()->id;
        $order_details = Order::with([
            'user:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,load_from',
            'order_submit_history',
            'rating:id,order_id,rating,sender_type',
            'order_mile_stones'])
            ->where('id',$id)
            ->where('freelancer_id',$freelancer_id)
            ->first();

        $order_type = Order::select(['id','is_project_job'])->where('id',$id)->first();

        if($order_type->is_project_job == 'offer'){
            $order_details = Order::with([
                'user:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,load_from',
                'order_submit_history.request_revision',
                'rating:id,order_id,rating,sender_type',
                'order_mile_stones'])
                ->where('id',$id)
                ->where('freelancer_id',$freelancer_id)
                ->first();
        }else{
            $order_details = Order::with([
                'user:id,first_name,last_name,email,phone,country_id,state_id,city_id,image,username,load_from',
                'order_submit_history.request_revision',
                'hourly_work_history:id,order_id,start_date,end_date,hours_worked,notes',
                'rating:id,order_id,rating,sender_type',
                'order_mile_stones',
                'project:id,title',
                'job:id,title,hourly_rate,estimated_hours',
            ])
                ->where('id',$id)
                ->where('freelancer_id',$freelancer_id)
                ->first();
        }

        if($order_details?->user?->image){
            $order_details->user->cloud_link = render_frontend_cloud_image_if_module_exists('profile/'.$order_details?->user?->image, load_from: $order_details?->user?->load_from);
        }else{
            $order_details->user->cloud_link = null;
        }

        $order_details->order_submit_history->transform(function ($order_submit_history){
            if (! file_exists(base_path('../assets/uploads/attachment/order/' . $order_submit_history->attachment))) {
                $order_submit_history->attachment = "";
            }
            return $order_submit_history;
        });

        if($order_details){
            return response()->json([
                'order_details' => $order_details,
                'image_path' => asset('assets/uploads/profile/'.$order_details?->user?->image),
                'order_submit_history_path' => asset('assets/uploads/attachment/order/'),
                'country' => $order_details?->user?->user_country?->country,
                'state' => $order_details?->user?->user_state?->state,
                'city' => $order_details?->user?->user_city?->city,
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no order found.')]);
    }

    // order accept
    public function order_accept(Request $request)
    {
        //if order from job proposal then first find job_id from order and update the job current_status
        $freelancer_id = auth('sanctum')->user()->id;
        $find_order = Order::where('id',$request->order_id)->where('status',0)->where('freelancer_id',$freelancer_id)->where('payment_status','complete')->first();

        if($find_order){
            if($find_order && $find_order->is_project_job == 'job'){
                JobPost::where('id',$find_order->identity)->update(['current_status'=>1]);
            }

            Order::where('id',$request->order_id)->update(['status'=>1]);
            $order_milestone = OrderMilestone::where('order_id',$request->order_id)->first();
            if($order_milestone){
                OrderMilestone::where('id',$order_milestone->id)->update(['status'=>1]);
            }
            return response()->json(['msg' => __('Order Successfully Accepted.')]);
        }else{
            return response()->json(['msg' => __('Order not found.')])->setStatusCode('422');
        }
    }

    // cancel & decline
    public function order_decline(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'cancel_or_decline_order' => 'required',
        ]);

        if($request->cancel_or_decline_order == 'decline' || $request->cancel_or_decline_order == 'cancel') {
            $freelancer_id = auth('sanctum')->user()->id;
            $find_order = Order::where('id', $request->order_id)->where('freelancer_id', $freelancer_id)->where('payment_status', 'complete')->whereIn('status', [0, 1])->first();

            if ($find_order) {
                $order_details = Order::select(['id', 'freelancer_id', 'user_id', 'price', 'payment_status'])->where('id', $request->order_id)->first();
                $cancel_or_decline = $request->cancel_or_decline_order;
                $cancel_or_decline == 'decline' ? Order::where('id', $request->order_id)->update(['status' => 5]) : Order::where('id', $request->order_id)->update(['status' => 4]);
                $msg = $cancel_or_decline == 'decline' ? __('Order decline by freelancer') : __('Order cancel by freelancer');
                $this->createDeclineWalletHistory($order_details->id, $order_details->freelancer_id, $order_details->user_id, $order_details->price, $order_details->payment_status, $cancel_or_decline, $msg);

                //update wallet balance
                if ($order_details->payment_status === 'complete') {
                    $user = Wallet::select('balance')->where('user_id', $order_details->user_id)->first();
                    Wallet::where('user_id', $order_details->user_id)->update([
                        'balance' => $user->balance + $order_details->price
                    ]);
                }
                return response()->json(['msg' => __('Order Successfully Decline.')]);
            } else {
                return response()->json(['msg' => __('Order not found.')])->setStatusCode('422');
            }
        }else{
            return response()->json(['msg' => __('Cancel or decline value must be decline or cancel.')])->setStatusCode('422');
        }
    }

    // order submit
    public function order_submit(Request $request)
    {
        $allowedSize = get_static_option('max_upload_size') ?? '1002400';
        $allowedExtensions = json_decode(get_static_option('file_extensions'), true);
        if($allowedExtensions){
            $allowed_extensions = implode(',', $allowedExtensions);
            $request->validate([
                'order_id'=>'required',
                'attachment' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                'description'=>'required|max:300'
            ]);
        }else{
            $request->validate([
                'order_id'=>'required',
                'attachment'=>'required|mimes:png,jpg,jpeg,pdf,docx,zip' . '|max:' . $allowedSize,
                'description'=>'required|max:300'
            ]);
        }


        $freelancer_id = auth('sanctum')->user()->id;
        $find_order = Order::with('order_mile_stones')
            ->where('freelancer_id',$freelancer_id)
            ->where('id',$request->order_id)
            ->where('status',1)
            ->first();

        if($find_order){
            $attachment = $request->attachment;
            $attachment_ext = $attachment->extension();
            $attachment_name = 'order_attachment_' . time() . '.' . $attachment_ext;
            $attachment_path = 'assets/uploads/attachment/order';
            $attachment->move($attachment_path, $attachment_name);

            OrderSubmitHistory::create([
                'order_id'=>$request->order_id,
                'order_milestone_id'=>$request->order_milestone_id,
                'description'=>$request->description,
                'attachment'=>$attachment_name,
            ]);

            $type = 'Order';
            $admin_msg = __('Order submitted by freelancer');
            $client_msg = __('Your order has been submitted. Please check it.');

            if($request->order_milestone_id){
                //update milestone status
                $find_milestone = OrderMilestone::where('id',$request->order_milestone_id)->where('order_id',$request->order_id)->first();
                if($find_milestone){
                    OrderMilestone::where('id',$request->order_milestone_id)->update(['status'=>4]);
                }else{
                    return response()->json(['msg' => __('Milestone not found.')])->setStatusCode('422');
                }
            }else{
                //update order status
                Order::where('id',$request->order_id)->update(['status'=>2]);
            }
            notificationToAdmin($request->order_id,$freelancer_id,$type,$admin_msg);
            client_notification($request->order_id, $find_order->user_id, $type, $client_msg);
            return response()->json(['msg' => __('Order Successfully Submitted')]);
        }else{
            return response()->json(['msg' => __('Order not found.')])->setStatusCode('422');
        }
    }

    // order decline wallet history
    private function createDeclineWalletHistory($order_id,$freelancer_id,$client_id,$order_price,$payment_status,$cancel_or_decline,$msg)
    {
        OrderDeclineHistory::create([
            'order_id'=>$order_id,
            'freelancer_id'=>$freelancer_id,
            'client_id'=>$client_id,
            'order_price'=>$order_price,
            'payment_status'=>$payment_status,
            'cancel_or_decline'=>$cancel_or_decline,
            'cancel_by'=>'freelancer',
        ]);

        OrderDeclineWalletHistory::create([
            'order_id'=>$order_id,
            'freelancer_id'=>$freelancer_id,
            'client_id'=>$client_id,
            'order_price'=>$order_price,
            'payment_status'=>$payment_status,
            'cancel_or_decline'=>$cancel_or_decline,
            'cancel_by'=>'freelancer',
        ]);

        notificationToAdmin($order_id,$freelancer_id,ucfirst($cancel_or_decline),$msg);
        client_notification($order_id, $client_id,'Order',__('Order cancel'));
    }
}
