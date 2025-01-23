<?php

namespace Modules\Service\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Service\Entities\Category;
use Modules\Subscription\Entities\Subscription;

class CategoryController extends Controller
{
    // add category
    public function all_category(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'category'=> 'required|unique:categories|max:191|regex:/\D+/',
                'short_description'=> 'required|max:191',
                'slug' => 'nullable|unique:categories|max:250',
                'meta_title' => 'nullable|max:250',
                'meta_description' => 'nullable|max:300',
            ]);

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->category);
            Category::create([
                'category' => $request->category,
                'short_description' => $request->short_description ?? $request->category,
                'slug' => purify_html($slug),
                'status' => $request->status,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'image' => $request->image,
            ]);
            toastr_success(__('New Category Successfully Added'));
        }
        $all_categories = Category::latest()->paginate(5);
        return view('service::category.all-category',compact('all_categories'));
    }

    public function select_category(Request $request)
    {
        $categories = Category::where('status', 1)->latest()->get();

        if($request->isMethod('post')){
            Category::query()->update(['selected_category' => 0]);
            $select_categories = $request->select_category;
            foreach ($select_categories as $categoryId) {
                Category::find($categoryId)->update(['selected_category' => 1]);
            }
            return back()->with(toastr_success(__('Selected category Successfully Updated.')));
        }
        return view('service::settings.category-select',compact('categories'));
    }

    // change category status
    public function change_status($id)
    {
        $category = Category::select('status')->where('id',$id)->first();
        $category->status==1 ? $status=0 : $status=1;
        Category::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // edit category
    public function edit_category(Request $request)
    {
        $request->validate([
            'edit_category'=> 'required|max:191|regex:/\D+/|unique:categories,category,'.$request->category_id,
            'edit_short_description'=> 'required|max:191',
            'edit_slug'=> 'required|max:191|unique:categories,slug,'.$request->category_id,
            'edit_meta_title' => 'nullable|max:250',
            'edit_meta_description' => 'nullable|max:300',
        ]);

        $slug = !empty($request->edit_slug) ? $request->edit_slug : Str::slug($request->edit_category);
        Category::where('id',$request->category_id)->update([
            'category'=>$request->edit_category,
            'short_description'=>$request->edit_short_description,
            'slug' => Str::slug(purify_html($slug),'-',null),
            'meta_title' => $request->edit_meta_title,
            'meta_description' => $request->edit_meta_description,
            'image' => $request->image,
        ]);
        return redirect()->back()->with(toastr_success(__('Category Successfully Updated')));
    }

    // delete category
    public function delete_category($id)
    {
        $category = Category::find($id);
        $subcategory_count = $category->sub_categories?->count();
        $job_count = $category->jobs?->count();
        $project_count = $category->projects?->count();
        $skill_count = $category->skills?->count();
        return $this->filter_and_delete_category($category,$subcategory_count,$job_count,$project_count,$skill_count);
    }

    // bulk action category
    public function bulk_action_category(Request $request){

        foreach($request->ids as $category_id){
            $category = Category::find($category_id);
            $subcategory_count = $category->sub_categories?->count();
            $job_count = $category->jobs?->count();
            $project_count = $category->projects?->count();
            $skill_count = $category->skills?->count();
            $this->filter_and_delete_category($category,$subcategory_count,$job_count,$project_count,$skill_count);
        }
        return redirect()->back()->with(toastr_error(__('Selected Category Successfully Deleted')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_categories = Category::latest()->paginate(5);
            return view('service::category.search-result', compact('all_categories'))->render();
        }
    }

    // search category
    public function search_category(Request $request)
    {
        $all_categories = Category::where('category', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(5);
        return $all_categories->total() >= 1 ? view('service::category.search-result', compact('all_categories'))->render() : response()->json(['status'=>__('nothing')]);
    }

    private function filter_and_delete_category($category,$subcategory_count,$job_count,$project_count,$skill_count)
    {
        if($subcategory_count > 0){
            return back()->with(toastr_error(__('Category is not deletable because it is related to subcategories')));
        }elseif($job_count > 0){
            return back()->with(toastr_error(__('Category is not deletable because it is related to jobs')));
        }elseif($project_count > 0){
            return back()->with(toastr_error(__('Category is not deletable because it is related to projects')));
        }elseif($skill_count > 0){
            return back()->with(toastr_error(__('Category is not deletable because it is related to skills')));
        }else{
            $category->delete();
            return redirect()->back()->with(toastr_error(__('Category Successfully Deleted')));
        }
    }

    public function enable_disable(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'category_section_enable_disable_for_homepage' => 'nullable|string',
                'category_section_enable_disable' => 'nullable|string'
            ]);
            $all_fields = ['category_section_enable_disable_for_homepage','category_section_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Category Section Enable Disable Updated Successfully.'));
            return back();
        }
        return view('service::settings.category-enable-disable');
    }
}
