<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Helper\PaymentGatewayRequestHelper;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Modules\PromoteFreelancer\Entities\ProjectPromoteSettings;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;
use Modules\Wallet\Entities\Wallet;

class BuyPromotePackageController extends Controller
{
    public function package_list()
    {
        if(moduleExists('PromoteFreelancer')){
            $all_package = ProjectPromoteSettings::select(['id','title','budget','duration'])
                ->where('status',1)
                ->paginate(10)
                ->withQueryString();

            return response()->json([
                'package_lists' => $all_package,
            ]);
        }else{
            return response()->json([
                'msg' => __('Please activate promote freelancer plugin first and try again'),
            ])->setStatusCode(422);
        }

    }

    //buy package
    public function buy_package(Request $request)
    {
        $request->validate([
            'selected_payment_gateway'=>'required|string',
            'package_id'=>'required|exists:project_promote_settings,id',
            'type' => 'required|string|in:profile,project',
            'identity' =>'required|integer|min:1',
        ]);

        $user_id = auth('sanctum')->id();
        if($request->type == 'project'){
            $project_count = Project::where('id', $request->identity)
                ->where('project_on_off',1)
                ->where('user_id',$user_id)
                ->where('status',1)->count();
            if($project_count < 1){
                return response()->json(['msg'=> __('Project not found')])->setStatusCode(422);
            }
        }else{
            $user_count = User::where('id', $request->identity)->where('id',$user_id)->where('user_type',2)->count();
            if($user_count < 1){
                return response()->json(['msg'=> __('User not found')])->setStatusCode(422);
            }
        }

        $all_gateway = ['wallet','paypal','manual_payment','mollie','paytm','stripe','razorpay','flutterwave','paystack','marcadopago','instamojo','cashfree','payfast','midtrans','squareup','cinetpay','paytabs','billplz','zitopay','sitesway','toyyibpay','authorize_dot_net'];
        if (!in_array($request->selected_payment_gateway, $all_gateway)) {
            return response()->json(['msg'=> __('Please select a payment gateway before place an order')])->setStatusCode('422');
        }

        if(isset($request->package_id)){
            $user = auth('sanctum')->user();
            $package_details = ProjectPromoteSettings::where('id',$request->package_id)->where('status','1')->first();

            if($package_details){
                $transaction_fee = $request->transaction_fee;
                if($request->selected_payment_gateway === 'manual_payment' || $request->selected_payment_gateway === 'wallet'){
                    $total = $package_details->budget;
                }else{
                    $total = $package_details->budget + $transaction_fee;
                }

                $current_date = Carbon::now();
                $expire_date = Carbon::now()->addDays($package_details->duration);
                $title = __('Buy Package');
                $duration = $package_details->duration;
                $name = $user->first_name.' '.$user->last_name;
                $email = $user->email;
                $user_type = $user->user_type == 1 ? 'client' : 'freelancer';
                $payment_status = $request->selected_payment_gateway === 'wallet' ? 'complete' : 'pending';
                $status = $request->selected_payment_gateway === 'wallet' ? 1 : 0;
                $identity = $request->identity;
                $type = $request->type;

                if($request->selected_payment_gateway === 'manual_payment')
                {
                    $find_package_for_profile = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','profile')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();

                    $find_package_for_project = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','project')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();

                    if($request->type == 'profile'){
                        if(!empty($find_package_for_profile)) {
                            return response()->json([
                                'msg' => __('Your profile is already in promotion.')
                            ])->setStatusCode(422);
                        }
                    }
                    if($request->type == 'project'){
                        if(!empty($find_package_for_project)) {
                            return response()->json([
                                'msg' => __('This project is already in promotion.')
                            ])->setStatusCode(422);
                        }
                    }

                    $request->validate(['manual_payment_image' => 'required|mimes:jpg,jpeg,png,pdf,svg']);
                    if($request->hasFile('manual_payment_image')){
                        $manual_payment_image = $request->manual_payment_image;
                        $img_ext = $manual_payment_image->extension();

                        $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;
                        if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
                            $manual_image_path = 'assets/uploads/manual-payment/promotion';

                            if (in_array($img_ext,['jpg','jpeg','png'])) {
                                $resize_full_image = Image::make($request->manual_payment_image);
                                $resize_full_image->save($manual_image_path .'/'. $manual_payment_image_name);
                            }else{
                                $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
                            }

                            $buy_package = PromotionProjectList::create([
                                'user_id' => $user->id,
                                'identity' => $identity,
                                'type' => $type,
                                'package_id' => $package_details->id,
                                'price' => $total,
                                'duration' => $duration,
                                'expire_date' => $expire_date,
                                'payment_gateway' => $request->selected_payment_gateway,
                                'manual_payment_image' => $manual_payment_image_name,
                                'payment_status' => $payment_status,
                                'status' => $status,
                                'is_valid_payment' => 'yes',
                            ]);
                            $last_package_id = $buy_package->id;
                            $this->adminNotification($last_package_id,$user->id);
                        }else{
                            return response()->json([
                                'msg'=> __('Image type not supported.')
                            ])->setStatusCode(422);
                        }
                    }

                    if($type == 'profile'){
                        User::where('id',$user->id)->update([
                            'is_pro' => 'no',
                            'pro_expire_date' => $expire_date
                        ]);
                    }else{
                        Project::where('id',$identity)->update([
                            'is_pro' => 'no',
                            'pro_expire_date' => $expire_date
                        ]);
                    }

                    $this->sendEmail($name,$last_package_id,$email);
                    return response()->json([
                        'msg'=> __('Package purchase success. Your package will be active after admin complete the payment status pending to complete.')
                    ]);

                }
                elseif($request->selected_payment_gateway === 'wallet')
                {
                    $find_package_for_profile = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','profile')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();

                    $find_package_for_project = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','project')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();


                    if($request->type == 'profile'){
                        if(!empty($find_package_for_profile)){
                            return response()->json([
                                'msg'=> __('Your profile is already in promotion.')
                            ])->setStatusCode(422);
                        }else{
                            $wallet_balance = Wallet::select('balance')->where('user_id',$user->id)->first();
                            if(isset($wallet_balance) && $wallet_balance->balance > $total){
                                $buy_package = PromotionProjectList::create([
                                    'user_id' => $user->id,
                                    'identity' => $identity,
                                    'type' => $type,
                                    'package_id' => $package_details->id,
                                    'price' => $total,
                                    'duration' => $duration,
                                    'expire_date' => $expire_date,
                                    'payment_gateway' => $request->selected_payment_gateway,
                                    'payment_status' => $payment_status,
                                    'status' => $status,
                                    'is_valid_payment' => 'yes',
                                ]);
                                $last_package_id = $buy_package->id;
                                $this->adminNotification($last_package_id,$user->id);
                                Wallet::where('user_id',$user->id)->update(['balance'=> $wallet_balance->balance - $total]);

                            }else{
                                return response()->json([
                                    'msg'=> __('Please deposit to your wallet and try again.')
                                ])->setStatusCode('422');
                            }
                            if($type == 'profile'){
                                User::where('id',$user->id)->update([
                                    'is_pro' => 'yes',
                                    'pro_expire_date' => $expire_date
                                ]);
                            }else{
                                Project::where('id',$identity)->update([
                                    'is_pro' => 'yes',
                                    'pro_expire_date' => $expire_date
                                ]);
                            }
                            $this->sendEmail($name,$last_package_id,$email);
                            return response()->json([
                                'msg'=> __('Promote package purchase success.')
                            ]);
                        }
                    }

                    if($request->type == 'project'){

                        if(!empty($find_package_for_project)){
                            return response()->json([
                                'msg'=> __('This project is already in promotion.')
                            ])->setStatusCode(422);
                        }else{

                            $wallet_balance = Wallet::select('balance')->where('user_id',$user->id)->first();
                            if(isset($wallet_balance) && $wallet_balance->balance > $total){
                                $buy_package = PromotionProjectList::create([
                                    'user_id' => $user->id,
                                    'identity' => $identity,
                                    'type' => $type,
                                    'package_id' => $package_details->id,
                                    'price' => $total,
                                    'duration' => $duration,
                                    'expire_date' => $expire_date,
                                    'payment_gateway' => $request->selected_payment_gateway,
                                    'payment_status' => $payment_status,
                                    'status' => $status,
                                    'is_valid_payment' => 'yes',
                                ]);
                                $last_package_id = $buy_package->id;
                                $this->adminNotification($last_package_id,$user->id);
                                Wallet::where('user_id',$user->id)->update(['balance'=> $wallet_balance->balance - $total]);

                            }else{
                                return response()->json([
                                    'msg'=> __('Please deposit to your wallet and try again.')
                                ])->setStatusCode('422');
                            }
                            if($type == 'profile'){
                                User::where('id',$user->id)->update([
                                    'is_pro' => 'yes',
                                    'pro_expire_date' => $expire_date
                                ]);
                            }else{
                                Project::where('id',$identity)->update([
                                    'is_pro' => 'yes',
                                    'pro_expire_date' => $expire_date
                                ]);
                            }
                            $this->sendEmail($name,$last_package_id,$email);
                            return response()->json([
                                'msg'=> __('Promote package purchase success.')
                            ]);
                        }
                    }
                }
                else
                {
                    $find_package_for_profile = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','profile')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();
                    $find_package_for_project = PromotionProjectList::where('user_id',$user->id)
                        ->where('identity',$identity)
                        ->where('type','project')
                        ->where('payment_status','complete')
                        ->where('expire_date','>=',$current_date)
                        ->first();

                    if($request->type == 'profile'){
                        if(!empty($find_package_for_profile)){
                            return response()->json([
                                'msg'=> __('Your profile is already in promotion.')
                            ])->setStatusCode(422);
                        }else{
                            $buy_package = PromotionProjectList::create([
                                'user_id' => $user->id,
                                'identity' => $identity,
                                'type' => $type,
                                'package_id' => $package_details->id,
                                'price' => $total,
                                'transaction_fee' => $transaction_fee,
                                'duration' => $duration,
                                'expire_date' => $expire_date,
                                'payment_gateway' => $request->selected_payment_gateway,
                                'payment_status' => $payment_status,
                                'status' => $status,
                            ]);

                            $last_package_id = $buy_package->id;
                            $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'),$last_package_id,$email,$name);
                            $promotion_details = PromotionProjectList::where('id',$last_package_id)->first();

                            return response()->json([
                                'msg'=> __('Promote package purchase success.'),
                                'type' => $type,
                                'promotion_details'=> $promotion_details,
                            ]);
                        }
                    }

                    if($request->type == 'project'){
                        if(!empty($find_package_for_project)){
                            return response()->json([
                                'msg'=> __('This project is already in promotion.')
                            ])->setStatusCode(422);
                        }else{
                            $buy_package = PromotionProjectList::create([
                                'user_id' => $user->id,
                                'identity' => $identity,
                                'type' => $type,
                                'package_id' => $package_details->id,
                                'price' => $total,
                                'transaction_fee' => $transaction_fee,
                                'duration' => $duration,
                                'expire_date' => $expire_date,
                                'payment_gateway' => $request->selected_payment_gateway,
                                'payment_status' => $payment_status,
                                'status' => $status,
                            ]);

                            $last_package_id = $buy_package->id;
                            $description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'),$last_package_id,$email,$name);
                            $promotion_details = PromotionProjectList::where('id',$last_package_id)->first();

                            return response()->json([
                                'msg'=> __('Promote package purchase success.'),
                                'type' => $type,
                                'promotion_details'=> $promotion_details,
                            ]);
                        }
                    }

                }
            }
        }

    }

    public function payment_update(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required',
            'status' => 'required',
            'secret_key' => 'required',
        ]);

        $promoted_package_details = PromotionProjectList::find($request->promotion_id);

        if (!empty($promoted_package_details) && $promoted_package_details->payment_status == 'pending' && $request->status == 1) {
            $last_promotion_id = $promoted_package_details->id;
            $user_id = $promoted_package_details->user_id;
            $freelancer = User::select(['id', 'first_name', 'last_name', 'email'])->where('id', $user_id)->first();

            $data_to_hash = $freelancer->email;
            $ctx = hash_init('sha256', HASH_HMAC, 'apipkey');
            hash_update($ctx, $data_to_hash);
            $secret_key = hash_final($ctx);

            if ($request->secret_key == $secret_key) {
                PromotionProjectList::where('id', $last_promotion_id)->where('user_id', $promoted_package_details->user_id)
                    ->update([
                        'payment_status' => 'complete',
                        'status' => 1,
                        'transaction_id' => 'transsactionidasdas234sad34',
                        'is_valid_payment' => 'yes',
                    ]);
            } else {
                return response()->json([
                    'msg' => __('Key does not match')
                ])->setStatusCode(422);
            }

            AdminNotification::create([
                'identity' => $promoted_package_details->identity,
                'user_id' => $promoted_package_details->user_id,
                'type' => __('Buy Package'),
                'message' => __('Promotion package purchase'),
            ]);
            if ($promoted_package_details->type == 'profile') {
                User::where('id', $promoted_package_details->user_id)->update([
                    'is_pro' => 'yes',
                    'pro_expire_date' => $promoted_package_details->expire_date
                ]);
            } else {
                Project::where('id', $promoted_package_details->identity)->update([
                    'is_pro' => 'yes',
                    'pro_expire_date' => $promoted_package_details->expire_date
                ]);
            }
            return response()->json([
                'status' => __('success'),
                'msg' => __('Promotion Status Updated Successfully')
            ]);
        }else{
            return response()->json([
                'msg' => __('Promotion id not found.')
            ])->setStatusCode(422);
        }
    }

    //send email
    private function sendEmail($name,$last_package_id,$email)
    {
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
            $message = str_replace(["@name","@package_id"],[$name, $last_package_id], $message);
            Mail::to($email)->send(new BasicMail([
                'subject' => get_static_option('user_promote_package_purchase_subject') ?? __('Promotion package purchase email'),
                'message' => $message
            ]));
        } catch (\Exception $e) {}
    }

    //admin notification
    private function adminNotification($last_package_id,$user_id)
    {
        AdminNotification::create([
            'identity'=>$last_package_id,
            'user_id'=>$user_id,
            'type'=>__('Buy Package'),
            'message'=>__('Promotion package purchase'),
        ]);
    }
}
