<?php

namespace Modules\Service\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Service\Entities\SubCategory;

class SubCategoryController extends Controller
{
    // add subcategory
    public function all_subcategory(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'sub_category'=> 'required|regex:/\D+/|unique:sub_categories|max:191',
                'short_description'=> 'required|max:191',
                'slug' => 'nullable|unique:sub_categories|max:191',
                'meta_title' => 'nullable|max:250',
                'meta_description' => 'nullable|max:300',
            ]);

            $slug = !empty($request->slug) ? $request->slug : $request->sub_category;
            SubCategory::create([
                'sub_category' => $request->sub_category,
                'short_description' => $request->short_description,
                'slug' => Str::slug(purify_html($slug),'-',null),
                'category_id' => $request->category,
                'status' => $request->status,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'image' => $request->image,
            ]);
            toastr_success(__('New Sub Category Successfully Added'));
        }
        $all_subcategories = SubCategory::with('category')->latest()->paginate(10);
        return view('service::subcategory.all-subcategory',compact('all_subcategories'));
    }

    // edit subcategory
    public function edit_subcategory(Request $request)
    {
        $request->validate([
            'edit_sub_category'=> 'required|max:191|regex:/\D+/|unique:sub_categories,sub_category,'.$request->edit_sub_category_id,
            'edit_short_description'=> 'required|max:191',
            'edit_slug'=> 'required|max:191|unique:sub_categories,slug,'.$request->edit_sub_category_id,
            'edit_meta_title' => 'nullable|max:250',
            'edit_meta_description' => 'nullable|max:300',
        ]);

        $slug = !empty($request->edit_slug) ? $request->edit_slug : $request->edit_sub_category;
        SubCategory::where('id',$request->edit_sub_category_id)->update([
            'sub_category'=>$request->edit_sub_category,
            'short_description'=>$request->edit_short_description,
            'slug' => Str::slug(purify_html($slug),'-',null),
            'category_id'=>$request->edit_category,
            'meta_title' => $request->edit_meta_title,
            'meta_description' => $request->edit_meta_description,
            'image' => $request->image,
        ]);
        return redirect()->back()->with(toastr_success(__('Subcategory Successfully Updated')));
    }

    // change status
    public function change_status($id)
    {
        $subcategory = SubCategory::select('status')->where('id',$id)->first();
        $subcategory->status==1 ? $status=0 : $status=1;
        SubCategory::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // delete subcategory
    public function delete_subcategory($id)
    {
        $subcategory = SubCategory::find($id);
        $job_count = $subcategory->jobs?->count();
        $project_count = $subcategory->projects?->count();
        $skill_count = $subcategory->skills?->count();
        return $this->filter_and_delete_subcategory($subcategory,$job_count,$project_count,$skill_count);
    }

    // bulk action subcategory
    public function bulk_action_subcategory(Request $request){
        foreach($request->ids as $subcategory_id){
            $subcategory = SubCategory::find($subcategory_id);
            $job_count = $subcategory->jobs?->count();
            $project_count = $subcategory->projects?->count();
            $skill_count = $subcategory->skills?->count();
            $this->filter_and_delete_subcategory($subcategory,$job_count,$project_count,$skill_count);
        }
        return redirect()->back()->with(toastr_error(__('Selected Subcategory Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_subcategories = SubCategory::latest()->paginate(10);
            return view('service::subcategory.search-result', compact('all_subcategories'))->render();
        }
    }

    public function search_subcategory(Request $request)
    {
        $all_subcategories = SubCategory::where('sub_category', 'LIKE', "%". strip_tags($request->string_search) ."%") ->paginate(10);
        return $all_subcategories->total() >= 1 ? view('service::subcategory.search-result', compact('all_subcategories'))->render() : response()->json(['status'=>__('nothing')]);

    }

    private function filter_and_delete_subcategory($subcategory,$job_count,$project_count,$skill_count)
    {
        if($job_count > 0){
            return back()->with(toastr_error(__('Subcategory is not deletable because it is related to jobs')));
        }elseif($project_count > 0){
            return back()->with(toastr_error(__('Subcategory is not deletable because it is related to projects')));
        }elseif($skill_count > 0){
            return back()->with(toastr_error(__('Subcategory is not deletable because it is related to skills')));
        }else{
            $subcategory->delete();
            return redirect()->back()->with(toastr_error(__('Subcategory Successfully Deleted')));
        }
    }
}
