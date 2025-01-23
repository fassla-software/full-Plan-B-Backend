<?php

namespace Modules\YooMoneyPaymentGateway\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\BasicMail;
use App\Mail\OrderMail;
use App\Models\AdminNotification;
use App\Models\JobProposal;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;
use Modules\Subscription\Entities\UserSubscription;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;
use Session;
use App\Helper\PaymentGatewayRequestHelper;

class YoomoneyIpnController extends Controller
{
    protected function cancel_page()
    {
        return redirect()->route('client.wallet.deposit.payment.cancel.static');
    }

    public function yoomoney_ipn_for_all(Request $request)
    {
        $yoomoney = XgPaymentGateway::yoomoney();
        $yoomoney->setShopId(get_static_option('yoomoney_shop_id') ?? '');
        $yoomoney->setSecretKey(get_static_option('yoomoney_secret_key') ?? '');
        $yoomoney->setEnv(get_static_option('yoomoney_test_mode') == 'on' ? true : false);
        $yoomoney->setCurrency(self::globalCurrency());
        $yoomoney->setExchangeRate(self::usdConversionValue());
        $payment_data = $yoomoney->ipn_response();


        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            if ($payment_data['payment_type'] == 'freelancer-wallet') {
                $order_id = $payment_data['order_id'];
                $order_details = WalletHistory::find($payment_data['order_id']);
                $user_id = $order_details->user_id;
                $this->update_database($order_id, $payment_data['transaction_id']);
                $this->send_deposit_mail($order_id, $user_id);
                toastr_success('Your wallet credited successfully');
                return redirect()->route('freelancer.wallet.history');
            } elseif ($payment_data['payment_type'] == 'client-wallet') {
                $order_id = $payment_data['order_id'];
                $order_details = WalletHistory::find($payment_data['order_id']);
                $user_id = $order_details->user_id;
                $this->update_database($order_id, $payment_data['transaction_id']);
                $this->send_deposit_mail($order_id, $user_id);
                toastr_success('Your wallet credited successfully');
                return redirect()->route('client.wallet.history');
            } elseif ($payment_data['payment_type'] == 'order') {
                $order_id = $payment_data['order_id'];
                $order_details = Order::find($payment_data['order_id']);
                $user_id = $order_details->user_id;
                $freelancer_id = $order_details->freelancer_id;
                $project_or_job = $order_details->is_project_job;
                $proposal_id = session()->get('proposal_id_for_order');

                $this->update_database_for_order($order_id, $payment_data['transaction_id'], $user_id, $freelancer_id, $project_or_job, $proposal_id);
                $this->send_order_mail($order_id, $user_id, $freelancer_id);
                toastr_success('Order  successfully completed');
                $new_order_id = getLastOrderId($order_id);
                return redirect()->route('order.user.success.page', $new_order_id);
            } elseif ($payment_data['payment_type'] == 'subscription') {
                $order_id = $payment_data['order_id'];
                $order_details = UserSubscription::find($payment_data['order_id']);
                $user_id = $order_details->user_id;
                $user_type = session()->get('user_type');
                $this->update_database_subscription($order_id, $payment_data['transaction_id']);
                $this->send_jobs_mail_subscription($order_id, $user_id);
                toastr_success('Subscription purchase success');
                return redirect()->route($user_type . '.' . 'subscriptions.all');
            } elseif ($payment_data['payment_type'] == 'promotion') {
                $order_id = $payment_data['order_id'];
                $order_details = PromotionProjectList::find($payment_data['order_id']);
                $user_id = $order_details->user_id;
                $user_type = session()->get('user_type');
                $this->update_promotion_database($order_id, $payment_data['transaction_id']);
                $this->send_promotion_mail($order_id, $user_id);
                toastr_success('Promotion package purchase success');
                return redirect()->route($user_type . '.' . 'profile.details', auth()->user()->username);
            }
        }
        return $this->cancel_page();
    }

    private static function globalCurrency(){
        return get_static_option('site_global_currency');
    }

    private static function usdConversionValue(){
        return get_static_option('site_' . strtolower(self::globalCurrency()) . '_to_usd_exchange_rate');
    }

    private function update_database($last_deposit_id, $transaction_id)
    {
        $deposit_details = WalletHistory::find($last_deposit_id);
        if($deposit_details->payment_status != 'complete'){
            $wallet_details = Wallet::where('user_id',$deposit_details->user_id)->first();
            Wallet::where('user_id', $deposit_details->user_id)->update([
                'balance' => $wallet_details->balance + $deposit_details->amount,
                'remaining_balance' => $wallet_details->remaining_balance + $deposit_details->amount,
            ]);
            WalletHistory::where('id', $last_deposit_id)->update([
                'payment_status' => 'complete',
                'transaction_id' => $transaction_id,
                'status' => 1,
            ]);

            AdminNotification::create([
                'identity'=>$last_deposit_id,
                'user_id'=>$deposit_details->user_id,
                'type'=>__('Deposit Amount'),
                'message'=>__('User wallet deposit'),
            ]);
        }

    }

    public function send_deposit_mail($last_deposit_id,$user_id)
    {
        if(empty($last_deposit_id)){
            return redirect()->route('homepage');
        }
        $user = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();
        $deposit_details = WalletHistory::find($last_deposit_id);
        if($deposit_details->email_send != 'yes'){
            //Send deposit email to admin
            try {
                $message = get_static_option('user_deposit_to_wallet_message_admin') ?? __('A user deposit to his wallet.');
                $message = str_replace(["@name","@deposit_id"],[$user->first_name.' '.$user->last_name, $last_deposit_id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_deposit_to_wallet_subject_admin') ?? __('Deposit Amount'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {

            }

            //Send deposit email to client
            try {
                $message = get_static_option('user_deposit_to_wallet_message') ?? __('Your deposit amount successfully credited to your wallet.');
                $message = str_replace(["@name","@deposit_id"],[$user->first_name.' '.$user->last_name, $last_deposit_id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_deposit_to_wallet_subject') ?? __('Deposit Amount'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {

            }
            WalletHistory::where('id', $last_deposit_id)->update([
                'email_send' => 'yes',
            ]);
        }
    }

    private function update_database_for_order($last_order_id,$transaction_id,$user_id,$freelancer_id,$project_or_job,$proposal_id)
    {
        $order_info = Order::select('price','transaction_amount','payment_status')->where('id',$last_order_id)->first();
        if($order_info->payment_status != 'complete'){
            Order::where('id', $last_order_id)->where('user_id',$user_id)
                ->update([
                    'price' => $order_info->price - $order_info->transaction_amount,
                    'payment_status' => 'complete',
                    'status' => 0,
                    'transaction_id' => $transaction_id,
                ]);
            notificationToAdmin($last_order_id, $user_id,'Order',__('New order placed'));
            freelancer_notification($last_order_id, $freelancer_id,'Order',__('You have a new order'));

            //update job proposal (hired 0 to one) if the order created from job
            if($project_or_job == 'job'){
                JobProposal::where('id',$proposal_id)->update(['is_hired'=>1]);
            }
        }
    }

    public function send_order_mail($last_order_id,$user_id,$freelancer_id)
    {
        if(empty($last_order_id)){ return redirect()->route('homepage');}

        $client = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();
        $freelancer = User::select(['id','first_name','last_name','email'])->where('id',$freelancer_id)->first();

        $order_info = Order::select('id','email_send')->where('id',$last_order_id)->first();
        if($order_info->email_send != 'yes'){
            //email to admin
            try {
                Mail::to(get_static_option('site_global_email'))->send(new OrderMail($last_order_id,'admin'));
            } catch (\Exception $e) {}

            //email to client
            try {
                Mail::to($client->email)->send(new OrderMail($last_order_id,'client'));
            } catch (\Exception $e) {}

            //email to freelancer
            try {
                Mail::to($freelancer->email)->send(new OrderMail($last_order_id,'freelancer'));
            } catch (\Exception $e) {}

            Order::where('id', $last_order_id)->update([
                'email_send' => 'yes',
            ]);
        }
    }

    private function update_database_subscription($last_subscription_id, $transaction_id)
    {
        $subscription_details = UserSubscription::find($last_subscription_id);
        if($subscription_details->payment_status != 'complete'){
            UserSubscription::where('id', $last_subscription_id)->where('user_id',$subscription_details->user_id)
                ->update([
                    'payment_status' => 'complete',
                    'status' => 1,
                    'transaction_id' => $transaction_id,
                ]);

            AdminNotification::create([
                'identity'=>$last_subscription_id,
                'user_id'=>$subscription_details->user_id,
                'type'=>__('Buy Subscription'),
                'message'=>__('User subscription purchase'),
            ]);
        }

    }

    public function send_jobs_mail_subscription($last_subscription_id,$user_id)
    {
        if(empty($last_subscription_id)){ return redirect()->route('homepage');}

        $user = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();
        $subscription_details = UserSubscription::find($last_subscription_id);

        if($subscription_details->email_send != 'yes'){
            //Send subscription email to admin
            try {
                $message = get_static_option('user_subscription_purchase_admin_email_message') ?? __('A user just purchase a subscription.');
                $message = str_replace(["@name","@subscription_id"],[$user->first_name.' '.$user->last_name, $last_subscription_id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_subscription_purchase_admin_email_subject') ?? __('Subscription purchase email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            //Send subscription email to user
            try {
                $message = get_static_option('user_subscription_purchase_message') ?? __('Your subscription purchase successfully completed.');
                $message = str_replace(["@name","@subscription_id"],[$user->first_name.' '.$user->last_name, $last_subscription_id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_subscription_purchase_subject') ?? __('Subscription purchase email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            UserSubscription::where('id', $last_subscription_id)->update([
                'email_send' => 'yes',
            ]);
        }
    }

    private function update_promotion_database($last_package_id, $transaction_id)
    {
        $promoted_package_details = PromotionProjectList::find($last_package_id);
        if($promoted_package_details->payment_status != 'complete'){
            PromotionProjectList::where('id', $last_package_id)->where('user_id',$promoted_package_details->user_id)
                ->update([
                    'payment_status' => 'complete',
                    'status' => 1,
                    'transaction_id' => $transaction_id,
                    'is_valid_payment' => 'yes',
                ]);

            AdminNotification::create([
                'identity'=>$promoted_package_details->identity,
                'user_id'=>$promoted_package_details->user_id,
                'type'=>__('Buy Package'),
                'message'=>__('Promotion package purchase'),
            ]);

            if($promoted_package_details->type == 'profile'){
                User::where('id',$promoted_package_details->user_id)->update([
                    'is_pro' => 'yes',
                    'pro_expire_date' => $promoted_package_details->expire_date
                ]);
            }else{
                Project::where('id',$promoted_package_details->identity)->update([
                    'is_pro' => 'yes',
                    'pro_expire_date' => $promoted_package_details->expire_date
                ]);
            }
        }
    }

    public function send_promotion_mail($last_package_id,$user_id)
    {
        if(empty($last_package_id)){ return redirect()->route('homepage');}

        $user = User::select(['id','first_name','last_name','email'])->where('id',$user_id)->first();
        $promoted_package_details = PromotionProjectList::find($last_package_id);

        if($promoted_package_details->email_send != 'yes'){
            //Send purchase package email to admin
            try {
                $message = get_static_option('user_promote_package_purchase_message_admin') ?? __('A user just purchase a promotion package.');
                $message = str_replace(["@package_id"],[$last_package_id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_promote_package_purchase_subject_admin') ?? __('Promotion package purchase email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            //Send purchase package email to user
            try {
                $message = get_static_option('user_promote_package_purchase_message') ?? __('Your promotion package purchase successfully completed.');
                $message = str_replace(["@name","@package_id"],[$user->first_name.' '.$user->last_name, $last_package_id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_promote_package_purchase_subject') ?? __('Promotion package purchase email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            PromotionProjectList::where('id', $last_package_id)->update([
                'email_send' => 'yes',
            ]);
        }
    }

}