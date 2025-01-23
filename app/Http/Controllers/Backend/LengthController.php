<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Length;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LengthController extends Controller
{
    // add length
    public function all_length(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'length'=> 'required|unique:lengths|max:191|regex:/\D+/',
            ]);

            Length::create([
                'length' => $request->length,
            ]);
            toastr_success(__('New Length Successfully Added'));
        }
        $all_lengths = Length::latest()->paginate(10);
        return view('backend.pages.length.all-length',compact('all_lengths'));
    }

    // change length status
    public function change_status($id)
    {
        $length = Length::select('status')->where('id',$id)->first();
        $length->status === 1 ? $status=0 : $status=1;
        Length::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Length Successfully Changed')));
    }

    // edit length
    public function edit_length(Request $request)
    {
        $request->validate([
            'edit_length'=> 'required|max:191|regex:/\D+/|unique:lengths,length,'.$request->length_id,
        ]);

        Length::where('id',$request->length_id)->update([
            'length'=>$request->edit_length,
        ]);
        return redirect()->back()->with(toastr_success(__('Length Successfully Updated')));
    }

    // delete length
    public function delete_length($id)
    {
        $length = Length::find($id);
        $length->delete();
        return redirect()->back()->with(toastr_error(__('Length Successfully Deleted')));
    }

    // bulk action length
    public function bulk_action_length(Request $request){

        foreach($request->ids as $length_id){
            $length = Length::find($length_id);
            $length->delete();
        }
        return redirect()->back()->with(toastr_error(__('Selected Length Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_lengths = Length::latest()->paginate(10);
            return view('backend.pages.length.search-result', compact('all_lengths'))->render();
        }
    }

    // search category
    public function search_length(Request $request)
    {
        $all_lengths = Length::where('length', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_lengths->total() >= 1 ? view('backend.pages.length.search-result', compact('all_lengths'))->render() : response()->json(['status'=>__('nothing')]);
    }
}
