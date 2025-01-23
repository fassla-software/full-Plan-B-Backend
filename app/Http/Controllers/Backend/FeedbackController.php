<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function all_feedback()
    {
        $feedbacks = Feedback::with('user:id,image')->latest()->paginate(10);
        return view('backend.pages.feedback.all-feedback',compact('feedbacks'));
    }

    // change feedback status
    public function change_status($id)
    {
        $feedback = Feedback::select('status')->where('id',$id)->first();
        $feedback->status==1 ? $status=0 : $status=1;
        Feedback::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // edit feedback
    public function edit_feedback(Request $request)
    {
        $request->validate([
            'title' => 'required|min:6|max:191',
            'description' => 'required|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedback::where('id',$request->feedback_id)->update([
                'title' =>$request->title,
                'description' =>$request->description,
                'rating' =>$request->rating,
            ]);

        return !empty($feedback) ? response()->json(['status'=>'success']) : response()->json(['status'=>'failed','msg' => __('Something Went wrong')]);

    }

    // delete feedback
    public function delete_feedback($id)
    {
        Feedback::find($id)->delete();
        return back()->with(toastr_success(__('Feedback Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search)){
                $feedbacks = Feedback::with('user:id,image')->latest()->paginate(2);
            }else{
                $feedbacks = Feedback::with('user:id,image')->where('rating', $request->string_search)->paginate(2);
            }
            return view('backend.pages.feedback.search-result', compact('feedbacks'))->render();
        }
    }

    // search feedback
    public function search_feedback(Request $request)
    {
        if(!empty($request->string_search)){
            $feedbacks = Feedback::with('user:id,image')->where('rating', $request->string_search)->paginate(2);
        }else{
            $feedbacks = Feedback::with('user:id,image')->latest()->paginate(2);
        }
        return $feedbacks->total() >= 1 ? view('backend.pages.feedback.search-result', compact('feedbacks'))->render() : response()->json(['status'=>__('nothing')]);
    }

}
