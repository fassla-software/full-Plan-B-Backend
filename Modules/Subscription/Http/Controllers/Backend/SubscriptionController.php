<?php

namespace Modules\Subscription\Http\Controllers\Backend;

use App\Models\AdminNotification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Subscription\Entities\Subscription;
use Modules\Subscription\Entities\SubscriptionFeature;
use Modules\Subscription\Entities\SubscriptionType;

class SubscriptionController extends Controller
{
    public function all_subscription()
    {
        $all_subscriptions = Subscription::with('subscription_type')->latest()->paginate(10);
        return view('subscription::backend.subscription.all-subscription',compact('all_subscriptions'));
    }

    public function add_subscription(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'type'=> 'required',
                'title'=> ['required',Rule::unique('subscriptions')->where(fn ($query) => $query->where('subscription_type_id', request()->type)),'max:191'],
                'price'=> 'required|gt:0',
                'limit'=> 'required|gt:0',
                'logo'=>'required|exists:media_uploads,id',
                'feature'=> 'required|array',
                'status'=> 'nullable|array',
            ]);

            if ($request->file('logo')) {
                $request->validate([
                    'logo'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50',
                ]);
            }

            DB::beginTransaction();
            try {
                $subscription = Subscription::create([
                    'subscription_type_id' => $request->type,
                    'title' => $request->title,
                    'price' => $request->price,
                    'limit' => $request->limit,
                    'logo' => $request->logo ?? '',
                    'status' => 1,
                ]);

                $arr = [];
                foreach($request->feature as $key => $attr) {
                    $arr[] = [
                        'subscription_id' => $subscription->id,
                        'feature' => $request->feature[$key] ?? '',
                        'status' => $request->status[$key] ?? 'off',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $data = Validator::make($arr,["*.feature" => "required"]);
                $data->validated();
                SubscriptionFeature::insert($arr);
                toastr_success(__('New Subscription Successfully Added'));
                DB::commit();
            }catch(Exception $e){}
        }

        $all_types = SubscriptionType::all_types();
        return view('subscription::backend.subscription.add-subscription',compact('all_types'));
    }

    public function edit_subscription(Request $request,$id)
    {
        if($request->isMethod('post')){
            $request->validate([
                'title'=> ['required',Rule::unique('subscriptions')->where(fn ($query) => $query->where('subscription_type_id', request()->type))->ignore($id),'max:191'],
                'type'=> 'required',
                'price'=> 'required|gt:0',
                'limit'=> 'required|gt:0',
                'feature'=> 'required|array',
                'status'=> 'nullable|array',
                'logo'=>'required',
            ],[
                'title.unique'=> __('Title already exists for this subscription type')
            ]);

            if ($request->file('logo')) {
                $request->validate(['logo'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50']);
            }

            DB::beginTransaction();
            try {
                Subscription::where('id',$id)->update([
                    'subscription_type_id' => $request->type,
                    'title' => $request->title,
                    'price' => $request->price,
                    'limit' => $request->limit,
                    'logo' => $request->logo ?? '',
                ]);

                SubscriptionFeature::where('subscription_id',$id)->delete();

                $arr = [];
                foreach($request->feature as $key => $attr) {
                    $arr[] = [
                        'subscription_id' => $id,
                        'feature' => $request->feature[$key] ?? '',
                        'status' => $request->status[$key] ?? 'off',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $data = Validator::make($arr,["*.feature" => "required"]);
                $data->validated();
                SubscriptionFeature::insert($arr);
                toastr_success(__('Subscription Successfully Updated'));
                DB::commit();
            }catch(Exception $e){}
        }
        $all_types = SubscriptionType::all_types();
        $subscription_details = Subscription::with('features')->where('id',$id)->first();
        return !empty($subscription_details) ?  view('subscription::backend.subscription.edit-subscription',compact('all_types','subscription_details')) : back();
    }


    // search category
    public function search_subscription(Request $request)
    {
        $all_subscriptions = Subscription::whereHas('subscription_type', function ($query) use ($request){
            $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })
            ->with(['subscription_type' => function($query) use ($request){
                $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%");
            }])
            ->paginate(10);
        return $all_subscriptions->total() >= 1 ? view('subscription::backend.subscription.search-result', compact('all_subscriptions'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $request->string_search == ''
                ? $all_subscriptions = Subscription::with('subscription_type')->latest()->paginate(10)
                : $all_subscriptions = Subscription::whereHas('subscription_type', function ($query) use ($request){
                    $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%");
            })
            ->with(['subscription_type' => function($query) use ($request){
                $query->where('type', 'LIKE', "%". strip_tags($request->string_search) ."%");
            }])
           ->paginate(10);
            return view('subscription::backend.subscription.search-result', compact('all_subscriptions'))->render();
        }
    }

    // delete subscription
    public function delete_subscription($id)
    {
        $subscription = Subscription::find($id);
        $subscription_users = $subscription->user_subscriptions?->count();
        if($subscription_users == 0){
            $subscription->delete();
            return back()->with(toastr_error(__('Subscription Successfully Deleted')));
        }else{
            return back()->with(toastr_error(__('Subscription is not deletable because it is related to user subscriptions')));
        }
    }

    // bulk action subscription
    public function bulk_action_subscription(Request $request){
        foreach($request->ids as $subscription_id){
            $subscription = Subscription::find($subscription_id);
            $subscription_users = $subscription->user_subscriptions?->count();
            if($subscription_users == 0){
                $subscription->delete();
            }
        }
        return back()->with(toastr_error(__('Selected Subscriptions Successfully Deleted')));
    }

    // change subscription status
    public function status($id)
    {
        $subscription = Subscription::select('status')->where('id',$id)->first();
        $subscription->status == 1 ? $status=0 : $status=1;
        Subscription::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }
}
