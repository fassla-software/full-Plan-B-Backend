<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\Request;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class SkillController extends Controller
{
    // display and edit skill
   public function all_skill(Request $request)
   {
       if($request->isMethod('post')){
           $request->validate([
               'skill'=> 'required|unique:skills|max:191',
               'category'=> 'required',
           ]);
           Skill::create([
               'skill' => $request->skill,
               'category_id' => $request->category,
               'sub_category_id' => $request->subcategory,
               'status' => $request->status,
           ]);
           toastr_success(__('New Skill Successfully Added'));
       }
       $all_categories = Category::all_categories();
       $all_sub_categories = SubCategory::all_sub_categories();
       $all_skills = Skill::latest()->paginate(10);
       return view('backend.pages.skill.all-skill',compact('all_skills','all_categories','all_sub_categories'));
   }

    // edit skill
    public function edit_skill(Request $request)
    {
        $request->validate([
            'edit_skill'=> 'required|max:191|unique:skills,skill,'.$request->skill_id,
            'edit_category'=> 'required',
        ]);
        Skill::where('id',$request->skill_id)->update([
            'skill'=>$request->edit_skill,
            'category_id'=>$request->edit_category,
            'sub_category_id'=>$request->edit_sub_category,
        ]);
        return redirect()->back()->with(toastr_success(__('Skill Successfully Updated')));
    }

    // change status
    public function change_status($id)
    {
        $skill = Skill::select('status')->where('id',$id)->first();
        $skill->status==1 ? $status=0 : $status=1;
        Skill::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // delete single skill
    public function delete_skill($id)
    {
        $skill = Skill::find($id);
        $job_count = $skill->jobs?->count();
        return $this->filter_and_delete_skill($skill,$job_count);
    }

    // delete multi skill
    public function bulk_action_skill(Request $request){
        foreach($request->ids as $skill_id){
            $skill = Skill::find($skill_id);
            $job_count = $skill->jobs?->count();
            $this->filter_and_delete_skill($skill,$job_count);
        }
        return redirect()->back()->with(toastr_success(__('Selected Skill Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_skills = Skill::latest()->paginate(10);
            return view('backend.pages.skill.search-result', compact('all_skills'))->render();
        }
    }

    // search skill
    public function search_skill(Request $request)
    {
        $all_skills= Skill::where('skill', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_skills->total() >= 1 ? view('backend.pages.skill.search-result', compact('all_skills'))->render() : response()->json(['status'=>__('nothing')]);
    }

    private function filter_and_delete_skill($skill,$job_count)
    {
        if($job_count > 0){
            return back()->with(toastr_error(__('Skill is not deletable because it is related to jobs')));
        }else{
            $skill->delete();
            return redirect()->back()->with(toastr_error(__('Skill Successfully Deleted')));
        }
    }
}
