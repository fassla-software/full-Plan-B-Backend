<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ExperienceLevel;
use Illuminate\Http\Request;

class ExperienceLevelController extends Controller
{
    // add length
    public function all_level(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'level'=> 'required|unique:experience_levels|max:191|regex:/\D+/',
            ]);

            ExperienceLevel::create([
                'level' => $request->level,
            ]);
            toastr_success(__('New Level Successfully Added'));
        }
        $all_levels = ExperienceLevel::latest()->paginate(10);
        return view('backend.pages.level.all-level',compact('all_levels'));
    }

    // change level status
    public function change_status($id)
    {
        $length = ExperienceLevel::select('status')->where('id',$id)->first();
        $length->status === 1 ? $status=0 : $status=1;
        ExperienceLevel::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Level Status Successfully Changed')));
    }

    // edit level
    public function edit_level(Request $request)
    {
        $request->validate([
            'edit_level'=> 'required|max:191|regex:/\D+/|unique:experience_levels,level,'.$request->level_id,
        ]);

        ExperienceLevel::where('id',$request->level_id)->update([
            'level'=>$request->edit_level,
        ]);
        return redirect()->back()->with(toastr_success(__('Level Successfully Updated')));
    }

    // delete level
    public function delete_level($id)
    {
        $length = ExperienceLevel::find($id);
        $length->delete();
        return redirect()->back()->with(toastr_error(__('Level Successfully Deleted')));
    }

    // bulk action level
    public function bulk_action_level(Request $request){

        foreach($request->ids as $length_id){
            $length = ExperienceLevel::find($length_id);
            $length->delete();
        }
        return redirect()->back()->with(toastr_error(__('Selected Level Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_levels = ExperienceLevel::latest()->paginate(10);
            return view('backend.pages.level.search-result', compact('all_levels'))->render();
        }
    }

    // search category
    public function search_length(Request $request)
    {
        $all_lengths = ExperienceLevel::where('length', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_lengths->total() >= 1 ? view('backend.pages.length.search-result', compact('all_lengths'))->render() : response()->json(['status'=>__('nothing')]);
    }
}
