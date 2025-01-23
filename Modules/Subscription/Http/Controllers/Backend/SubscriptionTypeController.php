<?php

namespace Modules\Subscription\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Entities\SubscriptionType;

class SubscriptionTypeController extends Controller
{
    public function all_type(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'type'=> 'required|unique:subscription_types|max:191',
                'validity'=> 'required|integer|between:7,365'
            ],
                [
                    'validity.between' => __('Validity must be a number between 7 to 365 days'),
                ]);
            SubscriptionType::create([
                'type' => $request->type,
                'validity' => $request->validity,
            ]);
            toastr_success(__('New Type Successfully Added'));
            return back();
        }

        $all_types = SubscriptionType::latest()->paginate(10);
        return view('subscription::backend.type.all-type',compact('all_types'));
    }

    public function edit_type(Request $request)
    {
        $request->validate([
            'type'=> 'required||max:191|unique:subscription_types,type,'.$request->type_id,
            'validity'=> 'required|integer|between:7,365'
        ],
         [
            'validity.between' => __('Validity must be a number between 7 to 365 days'),
         ]);

        SubscriptionType::where('id',$request->type_id)->update([
            'type' => $request->type,
            'validity' => $request->validity,
        ]);
        toastr_success(__('Type Successfully Updated'));
        return back();
    }

    // search category
    public function search_type(Request $request)
    {
        $all_types = SubscriptionType::where('type', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_types->total() >= 1 ? view('subscription::backend.type.search-result', compact('all_types'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_types =  $request->string_search == '' ? SubscriptionType::latest()->paginate(10) : SubscriptionType::where('type', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
            return view('subscription::backend.type.search-result', compact('all_types'))->render();
        }
    }

    // delete subscription type
    public function delete_type($id)
    {
        $type = SubscriptionType::find($id);
        $type_subscriptions = $type->subscriptions?->count();
        if($type_subscriptions == 0){
            $type->delete();
            return back()->with(toastr_error(__('Type Successfully Deleted')));
        }else{
            return back()->with(toastr_error(__('Type is not deletable because it is related to other subscriptions')));
        }
    }

    // bulk action subscription type
    public function bulk_action_type(Request $request){
         foreach($request->ids as $type_id){
             $type = SubscriptionType::find($type_id);
             $type_subscriptions = $type->subscriptions?->count();
             if($type_subscriptions == 0){
                 $type->delete();
             }
         }
        return back()->with(toastr_error(__('Selected Type Successfully Deleted')));
    }
}
