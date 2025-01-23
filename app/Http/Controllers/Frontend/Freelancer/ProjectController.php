<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Project;
use App\Models\ProjectAttribute;
use App\Models\ProjectSubCategory;
use App\Models\ProjectHistory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Modules\Service\Entities\SubCategory;

class ProjectController extends Controller
{
    // project create
    public function create_project(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'category'=>'required',
                'project_title'=>'required|min:20|max:100',
                'project_description'=>'required|min:50',
                'slug'=>'required|max:191|unique:projects,slug',
                'image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                'basic_title'=>'required|max:191',
                'basic_regular_charge'=>'required|numeric|integer',
                'checkbox_or_numeric_title'=>'required|array|max:191',
                'meta_title'=>'nullable|max:255',
                'meta_description'=>'nullable|max:500',
            ]);

            if(get_static_option('project_auto_approval') == 'yes'){
                $project_auto_approval = 1;
                $project_approve_request = 1;
            }else{
                $project_auto_approval=0;
                $project_approve_request=0;
            }


            $standard_title = null;
            $premium_title = null;
            $standard_regular_charge = null;
            $standard_discount_charge = null;
            $premium_regular_charge = null;
            $premium_discount_charge = null;

            if($request->offer_packages_available_or_not == 1){
                $standard_title = $request->standard_title;
                $premium_title = $request->premium_title;
                $standard_regular_charge = $request->standard_regular_charge;
                $standard_discount_charge = $request->standard_discount_charge;
                $premium_regular_charge = $request->premium_regular_charge;
                $premium_discount_charge = $request->premium_discount_charge;
            }

            $user_id  = Auth::guard('web')->user()->id;
            $slug = !empty($request->slug) ? $request->slug : $request->project_title;

            DB::beginTransaction();
            try {
                $imageName = '';
                $upload_folder = 'project';
                $storage_driver = Storage::getDefaultDriver();

                if ($image = $request->file('image')) {
                    $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                    $resize_full_image = Image::make($request->image)
                        ->resize(750, 410);

                    if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                        add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                    }else{
                        $resize_full_image->save('assets/uploads/project' .'/'. $imageName);
                    }
                }

                $project = Project::create([
                    'user_id'=>$user_id,
                    'category_id'=>$request->category,
                    'title'=>$request->project_title,
                    'slug' => Str::slug(purify_html($slug),'-',null),
                    'description'=>$request->project_description,
                    'image'=>$imageName,
                    'basic_title'=>$request->basic_title,
                    'standard_title'=>$standard_title,
                    'premium_title'=>$premium_title,
                    'basic_revision'=>$request->basic_revision,
                    'standard_revision'=>$request->standard_revision,
                    'premium_revision'=>$request->premium_revision,
                    'basic_delivery'=>$request->basic_delivery,
                    'standard_delivery'=>$request->standard_delivery,
                    'premium_delivery'=>$request->premium_delivery,
                    'basic_regular_charge'=>$request->basic_regular_charge,
                    'basic_discount_charge'=>$request->basic_discount_charge,
                    'standard_regular_charge'=>$standard_regular_charge,
                    'standard_discount_charge'=>$standard_discount_charge,
                    'premium_regular_charge'=>$premium_regular_charge,
                    'premium_discount_charge'=>$premium_discount_charge,
                    'project_on_off'=>1,
                    'status'=>$project_auto_approval,
                    'project_approve_request'=>$project_approve_request,
                    'offer_packages_available_or_not'=>$request->offer_packages_available_or_not,
                    'meta_title'=>$request->meta_title,
                    'meta_description'=>$request->meta_description,
                    'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
                ]);
                $project->project_sub_categories()->attach($request->subcategory);

                $arr = [];
                foreach($request->checkbox_or_numeric_title as $key => $attr):
                    $attr_value = str_replace(" ", "_",strtolower($attr));
                    $fallback_value = $request->checkbox_or_numeric_select[$key] == 'checkbox' ? "off" : 0;
                    $arr[] = [
                        'user_id' => $user_id,
                        'create_project_id' => $project->id,
                        'check_numeric_title' => $attr,
                        'basic_check_numeric' => $request->$attr_value["basic"] ?? $fallback_value,
                        'standard_check_numeric' => $request->$attr_value["standard"] ?? $fallback_value,
                        'premium_check_numeric' => $request->$attr_value["premium"] ?? $fallback_value,
                        'type' => $request->checkbox_or_numeric_select[$key] ?? null,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ];
                endforeach;

                $data = Validator::make($arr,["*.basic_check_numeric" => "nullable"]);
                $data->validated();

                ProjectAttribute::insert($arr);

                //security manage
                if(moduleExists('SecurityManage')){
                    LogActivity::addToLog('Project create','Freelancer');
                }

                DB::commit();
            }catch(Exception $e){

                DB::rollBack();

                if ($request->file('image')) {
                    $delete_img = 'assets/uploads/project/'.$imageName;
                    File::delete($delete_img);
                }

                toastr_error(__('Basic check numeric field is required'));
                return back();
            }

            try {
                $message = get_static_option('project_create_email_message') ?? __('A new project is just created.');
                $message = str_replace(["@project_id"],[$project->id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('project_create_email_subject') ?? __('Project Create Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}

            //create project notification to admin
            AdminNotification::create([
                'identity'=>$project->id,
                'user_id'=>$user_id,
                'type'=>'Create Project',
                'message'=>__('A new project has been created'),
            ]);
            toastr_success(__('Project Successfully Created'));
            return redirect()->route('freelancer.profile.details', Auth::guard('web')->user()->username);
        }

        return view('frontend.user.freelancer.project.create.create-project');
    }

    // project edit
    public function edit_project(Request $request, $id)
    {
        $project_details = Project::with('project_attributes')
            ->where('user_id',Auth::guard('web')->user()->id)
            ->where('id',$id)->first();
        $get_sub_categories_from_project_category = SubCategory::where('category_id',$project_details->category_id)->get() ?? '';

        if($request->isMethod('post'))
        {
            $request->validate([
                'project_title'=>'required|min:20|max:100|unique:projects,title,'.$id,
                'project_description'=>'required|min:50',
                'slug'=>'required|max:191|unique:projects,slug,'.$id,
                'basic_title'=>'required|max:191',
                'basic_regular_charge'=>'required|numeric|integer',
                'checkbox_or_numeric_title'=>'required|array|max:191',
                'meta_title'=>'nullable|max:255',
                'meta_description'=>'nullable|max:500',
            ]);

            $standard_title = null;
            $premium_title = null;
            $standard_regular_charge = null;
            $standard_discount_charge = null;
            $premium_regular_charge = null;
            $premium_discount_charge = null;

            if($request->offer_packages_available_or_not == 1){
                $standard_title = $request->standard_title;
                $premium_title = $request->premium_title;
                $standard_regular_charge = $request->standard_regular_charge;
                $standard_discount_charge = $request->standard_discount_charge;
                $premium_regular_charge = $request->premium_regular_charge;
                $premium_discount_charge = $request->premium_discount_charge;
            }

            $user_id  = Auth::guard('web')->user()->id;
            $slug = !empty($request->slug) ? $request->slug : $request->project_title;
            $delete_old_img =  'assets/uploads/project/'.$project_details->image;
            DB::beginTransaction();
            try {
                $imageName = '';
                $upload_folder = 'project';

                if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                    if ($image = $request->file('image')) {
                        $request->validate([
                            'image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                        ]);
                        $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                        // Get the current image path from the database
                        $currentImagePath = $project_details->image;
                        // Delete the old image if it exists
                        if ($currentImagePath) {
                            delete_frontend_cloud_image_if_module_exists('project/'.$currentImagePath);
                        }
                        add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                    }else{
                        $imageName = $project_details->image;
                    }
                }else{
                    if ($image = $request->file('image')) {
                        $request->validate([
                            'image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                        ]);
                        if(file_exists($delete_old_img)){
                            File::delete($delete_old_img);
                        }
                        $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                        $resize_full_image = Image::make($request->image)
                            ->resize(750, 410);
                        $resize_full_image->save('assets/uploads/project' .'/'. $imageName);
                    }else{
                        $imageName = $project_details->image;
                    }
                }


                Project::where('id',$id)->update([
                    'user_id'=>$user_id,
                    'category_id'=>$request->category,
                    'title'=>$request->project_title,
                    'slug' => Str::slug(purify_html($slug),'-',null),
                    'description'=>$request->project_description,
                    'image'=>$imageName,
                    'basic_title'=>$request->basic_title,
                    'standard_title'=>$standard_title,
                    'premium_title'=>$premium_title,
                    'basic_revision'=>$request->basic_revision,
                    'standard_revision'=>$request->standard_revision,
                    'premium_revision'=>$request->premium_revision,
                    'basic_delivery'=>$request->basic_delivery,
                    'standard_delivery'=>$request->standard_delivery,
                    'premium_delivery'=>$request->premium_delivery,
                    'basic_regular_charge'=>$request->basic_regular_charge,
                    'basic_discount_charge'=>$request->basic_discount_charge,
                    'standard_regular_charge'=>$standard_regular_charge,
                    'standard_discount_charge'=>$standard_discount_charge,
                    'premium_regular_charge'=>$premium_regular_charge,
                    'premium_discount_charge'=>$premium_discount_charge,
                    'project_on_off'=>1,
                    'project_approve_request'=> $project_details->project_approve_request == 1 ? 1 : 0,
                    'offer_packages_available_or_not'=> $request->offer_packages_available_or_not ?? 0,
                    'meta_title'=>$request->meta_title,
                    'meta_description'=>$request->meta_description,
                ]);

                //update product pivot table data
                $project = Project::find($id);
                $project->project_sub_categories()->sync($request->subcategory);

                ProjectAttribute::where('create_project_id',$id)->delete();

                $arr = [];
                foreach($request->checkbox_or_numeric_title as $key => $attr):
                    $attr_value = str_replace(" ", "_",strtolower($attr));

                    $fallback_value = $request->checkbox_or_numeric_select[$key] == 'checkbox' ? "off" : 0;

                    $arr[] = [
                        'user_id' => $user_id,
                        'create_project_id' => $id,
                        'check_numeric_title' => $attr,
                        'basic_check_numeric' => $request->$attr_value["basic"] ?? $fallback_value,
                        'standard_check_numeric' => $request->$attr_value["standard"] ?? $fallback_value,
                        'premium_check_numeric' => $request->$attr_value["premium"] ?? $fallback_value,
                        'type' => $request->checkbox_or_numeric_select[$key] ?? null,
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ];

                endforeach;

                $data = Validator::make($arr,["*.basic_check_numeric" => "nullable"]);
                $data->validated();

                ProjectAttribute::insert($arr);

                $project_id_from_project_history_table = ProjectHistory::where('project_id', $id)->first();

                if(empty($project_id_from_project_history_table)){
                    ProjectHistory::Create([
                        'project_id'=>$project->id,
                        'user_id'=>$project->user_id,
                        'reject_count'=>0,
                        'edit_count'=>1,
                    ]);
                }else{
                    ProjectHistory::where('project_id',$id)->update([
                        'reject_count'=>$project_id_from_project_history_table->edit_count + 1
                    ]);
                }

                //security manage
                if(moduleExists('SecurityManage')){
                    LogActivity::addToLog('Project edit','Freelancer');
                }

                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                if ($request->file('image')) {
                    $delete_img = 'assets/uploads/project/'.$imageName;
                    File::delete($delete_img);
                }
                toastr_error(__('Basic check numeric field is required'));
                return back();
            }

            try {
                $message = get_static_option('project_edit_email_message') ?? __('A new project is just edited.');
                $message = str_replace(["@project_id"],[$id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('project_edit_email_subject') ?? __('Project Edit Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}

            //edit project notification to admin
            AdminNotification::create([
                'identity'=>$id,
                'user_id'=>$user_id,
                'type'=>'Edit Project',
                'message'=>__('A project has been edited.'),
            ]);

            toastr_success(__('Project Successfully Updated'));
            return redirect()->route('freelancer.profile.details', Auth::guard('web')->user()->username);
        }

        return view('frontend.user.freelancer.project.edit.edit-project',compact('project_details','get_sub_categories_from_project_category'));
    }

    // project preview
    public function project_preview()
    {
        $all_projects = Project::with('project_attributes')->where('user_id',Auth::guard('web')->user()->id)->latest()->get();
        return view('frontend.user.freelancer.project.preview.all-projects',compact('all_projects'));
    }

    // project description

    public function project_description(Request $request)
    {
        if($request->ajax()){
            $project_title_and_description = Project::select(['title','description'])->where('id',$request->project_id)->first();
            return view('frontend.user.freelancer.project.preview.project-description',compact('project_title_and_description'))->render();
        }
    }

    // project delete
    public function delete_project(Request $request)
    {
       $project = Project::findOrFail($request->project_id);
       ProjectAttribute::where('create_project_id',$project->id)->delete();
       ProjectHistory::where('project_id',$project->id)->delete();
        $project->delete();
        return response()->json(['status'=>'success']);
    }

}
