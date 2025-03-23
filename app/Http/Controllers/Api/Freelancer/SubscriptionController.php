<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Mail\BasicMail;
use App\Enums\OperationType;
use Illuminate\Support\Carbon;
use Modules\Wallet\Entities\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use App\Models\{AdminNotification, User, CommaConsume};
use Illuminate\Http\{JsonResponse, Request, Response};
use Modules\Subscription\Entities\{Subscription, SubscriptionType, UserSubscription};

class SubscriptionController extends Controller
{
    //all types
    public function types()
    {
        $subscription_types = SubscriptionType::whereHas('subscriptions')->select('id', 'type', 'validity')->get();
        return response()->json([
            'subscription_types' => $subscription_types,
        ]);
    }

    //all frontend subscription with filter
    public function all_front_subscription(Request $request)
    {
        $request->validate([
            'type_id' => 'required'
        ]);

        $type_id = $request->type_id;

        if ($type_id == 'all') {
            $query = Subscription::with(['subscription_type:id,type', 'features:id,subscription_id,feature,status'])
                ->select(['id', 'subscription_type_id', 'title', 'logo', 'price', 'limit'])
                ->where('status', 1)
                ->latest()
                ->paginate(10)->withQueryString();

            $subscriptions = $query->through(function ($item) {
                if (!empty($item->logo)) {
                    $img_details = get_attachment_image_by_id($item->logo);
                    $item->logo = $img_details['img_url'] ?? null;
                }
                return $item;
            });
        } else {
            $check_type = SubscriptionType::where('id', $type_id)->first();
            if ($check_type) {
                $query = Subscription::with(['subscription_type:id,type', 'features:id,subscription_id,feature,status'])
                    ->select(['id', 'subscription_type_id', 'title', 'logo', 'price', 'limit'])
                    ->where('status', 1)
                    ->where('subscription_type_id', $type_id)
                    ->latest()
                    ->paginate(10)->withQueryString();

                $subscriptions = $query->through(function ($item) {
                    if (!empty($item->logo)) {
                        $img_details = get_attachment_image_by_id($item->logo);
                        $item->logo = $img_details['img_url'] ?? null;
                    }
                    return $item;
                });
            } else {
                return response()->json([
                    'msg' => __('Type not found')
                ]);
            }
        }

        return response()->json([
            'subscriptions' => $subscriptions,
        ]);
    }

    //below routes for auth user
    //freelancer subscription history list
    // i should remove the total lemit from here
    public function all_subscription()
    {
        $user_id = auth('sanctum')->user()->id;
        $all_subscriptions = UserSubscription::select('id', 'user_id', 'subscription_id', 'price', 'limit', 'status', 'payment_status', 'payment_gateway', 'expire_date', 'created_at')
            ->with(['user_subscription_type_api'])
            ->latest()
            ->where('user_id', $user_id)
            ->paginate(10)->withQueryString();

        $total_limit = UserSubscription::where('user_id', $user_id)
            ->where('payment_status', 'complete')
            ->whereDate('expire_date', '>', Carbon::now())
            ->latest()
            ->first()->limit;

        return response()->json([
            'all_subscriptions' => $all_subscriptions,
            'total_limit' => $total_limit,
        ]);
    }

    //buy subscription
    // check if he passed 90% from its fasslas
    public function buy_subscription(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required',
            'selected_payment_gateway' => 'required',
        ]);

        $all_gateway = payment_gateway_list_for_api();

        if (!in_array($request->selected_payment_gateway, $all_gateway)) {
            return response()->json(['msg' => __('Please select a valid payment gateway')])->setStatusCode(422);
        }

        if ($request->selected_payment_gateway === 'manual_payment') {
            $request->validate([
                'manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf'
            ]);
        }
        //get auth user
        $user = auth('sanctum')->user();

        $latest_subscription = $user->subscriptions()
            ->where('expire_date', '>', Carbon::now())
            ->latest()
            ->first();

        // check validation of usage 90% 
        $remaining_limits = 0;
        if ($latest_subscription) {
            if ($latest_subscription->limit > ($latest_subscription->subscription->limit - (($latest_subscription->subscription->limit * 90) / 100))) {
                return response()->json([
                    'message' => __('You can\'t make new subscription now, You did not consume 90% from you limits')
                ])->setStatusCode(422);
            } else {
                $remaining_limits = $latest_subscription->limit;
            }
        }

        $subscription_details = Subscription::with('subscription_type:id,validity')
            ->select(['id', 'subscription_type_id', 'price', 'limit'])
            ->where('id', $request->subscription_id)
            ->where('status', '1')->first();

        if ($subscription_details) {
            $expire_date = \Carbon\Carbon::now()->addDays($subscription_details?->subscription_type?->validity);
            $title = __('Buy Subscription');
            $total = $subscription_details->price;
            $limit = $subscription_details->limit;
            $name = $user->first_name . ' ' . $user->last_name;
            $email = $user->email;
            $user_type = 'freelancer';
            $payment_status = $request->selected_payment_gateway === 'wallet' ? 'complete' : 'pending';
            $status = $request->selected_payment_gateway === 'wallet' ? 1 : 0;

            if ($request->selected_payment_gateway === 'manual_payment') {
                $request->validate(['manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf']);

                if ($request->hasFile('manual_payment_image')) {
                    $manual_payment_image = $request->manual_payment_image;
                    $img_ext = $manual_payment_image->extension();

                    $manual_payment_image_name = 'manual_attachment_' . time() . '.' . $img_ext;
                    if (in_array($img_ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
                        $manual_image_path = 'assets/uploads/manual-payment/subscription';

                        if (in_array($img_ext, ['jpg', 'jpeg', 'png'])) {
                            $resize_full_image = Image::make($request->manual_payment_image);
                            $resize_full_image->save($manual_image_path . '/' . $manual_payment_image_name);
                        } else {
                            $manual_payment_image->move($manual_image_path, $manual_payment_image_name);
                        }
                        $buy_subscription = UserSubscription::create([
                            'user_id' => $user->id,
                            'subscription_id' => $subscription_details->id,
                            'price' => $total,
                            'limit' => $limit,
                            'remining_limit' => $remaining_limits,
                            'expire_date' => $expire_date,
                            'payment_gateway' => $request->selected_payment_gateway,
                            'manual_payment_payment' => $manual_payment_image,
                            'payment_status' => $payment_status,
                            'status' => $status,
                        ]);
                        $last_subscription_id = $buy_subscription->id;
                        $this->adminNotification($last_subscription_id, $user->id);
                    } else {
                        return response()->json([
                            'msg' => __('Image type not supported')
                        ])->setStatusCode(422);
                    }
                }
                $this->sendEmail($name, $last_subscription_id, $email);

                return response()->json([
                    'msg' => __('Subscription purchase success. Your subscription will be usable after admin approval')
                ]);
            } elseif ($request->selected_payment_gateway === 'wallet') {
                $wallet_balance = Wallet::select('balance')->where('user_id', $user->id)->first();
                if (isset($wallet_balance) && $wallet_balance->balance > $total) {
                    $buy_subscription = UserSubscription::create([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription_details->id,
                        'price' => $total,
                        'limit' => $limit,
                        'remining_limit' => $remaining_limits,
                        'expire_date' => $expire_date,
                        'payment_gateway' => $request->selected_payment_gateway,
                        'payment_status' => $payment_status,
                        'status' => $status,
                    ]);
                    $last_subscription_id = $buy_subscription->id;
                    $this->adminNotification($last_subscription_id, $user->id);
                    Wallet::where('user_id', $user->id)->update(['balance' => $wallet_balance->balance - $total]);
                } else {
                    return response()->json([
                        'msg' => __('Please deposit to your wallet and try again.')
                    ])->setStatusCode(422);
                }
                $this->sendEmail($name, $last_subscription_id, $email);
                return response()->json([
                    'msg' => __('Subscription purchase success.')
                ]);
            } else {
                $buy_subscription = UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription_details->id,
                    'price' => $total,
                    'limit' => $limit,
                    'remining_limit' => $remaining_limits,
                    'expire_date' => $expire_date,
                    'payment_gateway' => $request->selected_payment_gateway,
                    'payment_status' => $payment_status,
                    'status' => $status,
                ]);

                $last_subscription_id = $buy_subscription->id;
                $last_subscription_details = UserSubscription::where('id', $last_subscription_id)->first();

                return response()->json([
                    'subscription_details' => $last_subscription_details,
                    'msg' => __('Subscription purchase success.')
                ]);
            }
        }

        return response()->json([
            'msg' => __('Subscription not found!'),
        ])->setStatusCode(422);
    }

    public function get_current_subscription_details(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $crrent_subscription = getCurrentUserSubsicription($user);

        if (!isset($crrent_subscription)) return response()->json(null);

        $used = ($crrent_subscription->limit - $crrent_subscription->remining_limit) - $crrent_subscription->subscription->limit;
        $shifted_used = ($crrent_subscription->limit - $crrent_subscription->subscription->limit) - $crrent_subscription->remining_limit;

        return response()->json(
            [
                'message' => 'success',
                'data' => [
                    'user_id' => $user->id,
                    'package' => [
                        'package_name' => $crrent_subscription->subscription->title,
                        'package_limit' => $crrent_subscription->subscription->limit,
                        'package_used' => $used,
                        'package_remaining' => ($crrent_subscription->limit - $crrent_subscription->remining_limit),
                        'used_percentage' => floor(($used / $crrent_subscription->subscription->limit) * 100),
                    ],
                    'shifted_limit' => [
                        'total_shifted' => $crrent_subscription->remining_limit,
                        'used_shifted' => $shifted_used,
                    ],
                    'total' => [
                        'total_limit' => $crrent_subscription->limit,
                    ],
                    'expire_date' => $crrent_subscription->expire_date,
                ],
            ]
        );
    }

    public function get_consume_percentage(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $crrent_subscription = getCurrentUserSubsicription($user);

        if (!$crrent_subscription) return response()->json(['message' => 'no subscription found', 'data' => null], Response::HTTP_NOT_FOUND);

        $commaConsumes = CommaConsume::where('user_subscription_id', $crrent_subscription->id)
            ->with('operation_cost')
            ->get();

        $totalConsumedLimit = $commaConsumes->sum('consumed_limit');

        $operationPercentages = array_fill_keys(
            array_map(fn($case) => $case->name, OperationType::cases()),
            0
        );

        if ($totalConsumedLimit > 0) {
            foreach ($commaConsumes as $commaConsume) {
                $operationTypeValue = $commaConsume->operation_cost->operation_type;

                $operationTypeName = OperationType::from($operationTypeValue)->name;
                $operationPercentages[$operationTypeName] += $commaConsume->consumed_limit;
            }

            foreach ($operationPercentages as $operationType => &$consumed) {
                $consumed = round(($consumed / $totalConsumedLimit) * 100, 2);
            }
        }

        return response()->json(['message' => 'success', 'data' => $operationPercentages]);
    }

    //payment update
    public function payment_update(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required',
            'status' => 'required'
        ]);

        $user_id = auth('sanctum')->user()->id;
        $subscription_details = UserSubscription::where('id', $request->subscription_id)->where('user_id', $user_id)->first();
        $last_subscription_id = $subscription_details?->id;

        if (!empty($subscription_details) && $subscription_details->payment_status == 'pending' && $request->status == 1) {
            $client = User::select(['id', 'first_name', 'last_name', 'email'])->where('id', $user_id)->first();

            $data_to_hash = $client->email;
            $ctx = hash_init('sha256', HASH_HMAC, 'apiwalletkey');
            hash_update($ctx, $data_to_hash);
            $secret_key = hash_final($ctx);

            if ($request->secret_key == $secret_key) {

                UserSubscription::where('id', $last_subscription_id)->update([
                    'payment_status' => 'complete',
                    'status' => 1,
                ]);

                AdminNotification::create([
                    'identity' => $last_subscription_id,
                    'user_id' => $subscription_details->user_id,
                    'type' => __('Buy Subscription'),
                    'message' => __('User subscription purchase'),
                ]);
            } else {
                return response()->json([
                    'msg' => __('Key does not match')
                ])->setStatusCode(422);
            }
        } else {
            return response()->json([
                'msg' => __('Wallet history id not found')
            ]);
        }

        return response()->json([
            'status' => __('success'),
            'msg' => __('Deposit Status Updated Successfully')
        ]);
    }

    //send email
    private function sendEmail($name, $last_subscription_id, $email)
    {
        //Send subscription email to admin
        try {
            $message = get_static_option('user_subscription_purchase_admin_email_message') ?? __('A user just purchase a subscription.');
            $message = str_replace(["@name", "@subscription_id"], [$name, $last_subscription_id], $message);
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_admin_email_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {
        }

        //Send subscription email to user
        try {
            $message = get_static_option('user_subscription_purchase_message') ?? __('Your subscription purchase successfully completed.');
            $message = str_replace(["@name", "@subscription_id"], [$name, $last_subscription_id], $message);
            Mail::to($email)->send(new BasicMail([
                'subject' => get_static_option('user_subscription_purchase_subject') ?? __('Subscription purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {
        }
    }

    //admin notification
    private function adminNotification($last_subscription_id, $user_id)
    {
        AdminNotification::create([
            'identity' => $last_subscription_id,
            'user_id' => $user_id,
            'type' => __('Buy Subscription'),
            'message' => __('User subscription purchase'),
        ]);
    }
}
