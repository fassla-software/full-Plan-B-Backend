<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use App\Models\Order;
use App\Models\OrderMilestone;
use App\Models\OrderSubmitHistory;
use App\Models\UserEarning;
use Illuminate\Console\Command;
use Modules\Wallet\Entities\Wallet;

class OrderComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $active_deliver_orders =  Order::with(['order_submit_history','order_mile_stones'])->latest()->whereIn('status',[1,2])->get();
        foreach ($active_deliver_orders as $order){
          if($order?->order_mile_stones->count() >= 1 && $order?->order_submit_history->count() >= 1)
          {
              $milestone = OrderMilestone::where('order_id',$order->id)->where('status',4)->first();
              $order_submit_history = OrderSubmitHistory::where('order_id',$order?->id ?? 0)->where('order_milestone_id',$milestone?->id ?? 0)->where('status',0)->first();

              if(!empty($order_submit_history)){
                  if($order_submit_history->updated_at->addDay(get_static_option('order_auto_approval') ?? 30)->lessThan(\Carbon\Carbon::today())) {

                      $freelancer_id = Order::select('freelancer_id')->where('id',$milestone->order_id)->first();
                      $total_earning = UserEarning::where('user_id',$freelancer_id->freelancer_id)->first();

                      if($total_earning)
                      {
                          //update if freelancer has any earnings
                          UserEarning::where('user_id',$freelancer_id->freelancer_id)->update([
                              'total_earning'=>$total_earning->total_earning + $milestone->price,
                              'remaining_balance'=> ($total_earning->total_earning+$milestone->price) - $total_earning->total_withdraw
                          ]);
                      }
                      else
                      {
                          //Create if freelancer has no earnings
                          UserEarning::create([
                              'user_id' => $freelancer_id->freelancer_id,
                              'total_earning' => $milestone->price,
                              'remaining_balance' => $milestone->price
                          ]);
                      }

                      //update freelancer wallet balance
                      $freelancer_wallet = Wallet::where('user_id',$freelancer_id->freelancer_id)->first();
                      Wallet::where('user_id',$freelancer_id->freelancer_id)->update([
                          'balance'=>$freelancer_wallet->balance + $milestone->price,
                          'remaining_balance'=> $freelancer_wallet->remaining_balance+$milestone->price
                      ]);

                      //complete single milestone
                       OrderMilestone::where('id',$milestone->id)->update(['status'=>2]);

                      //approve submitted work
                      $order_submit_history = OrderSubmitHistory::where('order_milestone_id',$milestone->id)->OrderBy('id','DESC')->first();
                      OrderSubmitHistory::where('id',$order_submit_history->id)->update(['status'=>1]);

                      //active next milestone
                      $next_milestone = OrderMilestone::where('order_id',$milestone->order_id)->where('id', '>', $milestone->id)->min('id');

                      //freelancer and admin notification
                      freelancer_notification($milestone->order_id, $freelancer_id->freelancer_id,'Order',__('Your order has been completed by automated system'));
                      client_notification($milestone->order_id ?? $order->id, $order->user_id, 'Order',__('Your order has been completed by automated system'));
                      notificationToAdmin($milestone->order_id, $order->user_id,'Order',__('Order completed completed by automated system'));

                      if($next_milestone)
                      {
                          continue;
                      }
                      else
                      {
                          //update order status complete after complete all milestones
                          Order::where('id',$milestone->order_id)->update(['status'=>3]);
                      }
                  }

              }
          }
          else
          {
              if($order->updated_at->addDay(get_static_option('order_auto_approval') ?? 30)->lessThan(\Carbon\Carbon::today())){
                  $total_earning = UserEarning::where('user_id',$order->freelancer_id)->first();
                  if($total_earning){
                      //update total earning if freelancer has any earnings
                      UserEarning::where('user_id',$order->freelancer_id)->update([
                          'total_earning'=>$total_earning->total_earning + $order->payable_amount,
                          'remaining_balance'=> ($total_earning->total_earning+$order->payable_amount) - $total_earning->total_withdraw
                      ]);
                  }else{
                      //Create total earning if freelancer has no earnings
                      UserEarning::create([
                          'user_id'=>$order->freelancer_id,
                          'total_earning'=>$order->payable_amount,
                          'remaining_balance'=>$order->payable_amount
                      ]);
                  }

                  //update freelancer wallet balance
                  $freelancer_wallet = Wallet::where('user_id',$order->freelancer_id)->first();
                  Wallet::where('user_id',$order->freelancer_id)->update([
                      'balance'=>$freelancer_wallet->balance + $order->payable_amount,
                      'remaining_balance'=> $freelancer_wallet->remaining_balance+$order->payable_amount
                  ]);

                  //update order status to complete
                  Order::where('id',$order->id)->update(['status'=>3]);

                  //approve submitted work
                  $order_submit_history = OrderSubmitHistory::where('order_id',$order->id)->OrderBy('id','DESC')->first();
                  OrderSubmitHistory::where('id',$order_submit_history->id)->update(['status'=>1]);

                  //freelancer and admin notification
                  freelancer_notification($milestone->order_id ?? $order->id, $order->freelancer_id, 'Order',__('Your order has been completed by automated system'));
                  client_notification($milestone->order_id ?? $order->id, $order->user_id, 'Order',__('Your order has been completed by automated system.'));
                  notificationToAdmin($milestone->order_id ?? $order->id, $order->user_id,'Order',__('Order completed by automated system'));

                  //if order from job proposal then first find job_id from order and update the job current_status
                  $find_order = Order::findOrFail($order->id);
                  if($order && $order?->is_project_job == 'job'){
                      JobPost::where('id',$find_order->identity)->update(['current_status'=>2]);
                  }
              }
          }
        }
        return 0;
    }
}
