<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Project;
use App\Models\ProjectAttribute;
use App\Models\ProjectHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // project list
    public function project_list()
    {
        $user_id  = auth('sanctum')->user()->id;
        $project_lists = Project::select(['id','user_id','title','image','basic_delivery','basic_regular_charge','basic_discount_charge','status','project_on_off','is_pro','pro_expire_date','load_from'])
            ->withCount(['complete_orders','ratings'])->withAvg('ratings','rating')
            ->where('user_id', $user_id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            $project_lists->transform(function ($project) {
                $project->cloud_link = render_frontend_cloud_image_if_module_exists('project/'.$project->image, load_from: $project->load_from);
                return $project;
            });
        }

        return response()->json([
            'project_lists' => $project_lists,
            'project_image_path' => asset('assets/uploads/project/'),
            'freelancer_level' => freelancer_level_api($user_id) ?? '',
            'storage_driver' => Storage::getDefaultDriver() ?? '',
        ]);
    }

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
                'basic_revision'=>'required|numeric|integer|max:1000',
                'basic_regular_charge'=>'required|numeric|integer',
                'basic_delivery'=>'required|string|max:191',
                'checkbox_or_numeric_title'=>'required',
            ]);

            $user_id  = auth('sanctum')->user()->id;
            $slug = !empty($request->slug) ? $request->slug : $request->project_title;
            $generated_slug = Str::slug(purify_html($slug));

            $slugs = Project::select('slug')->get();
            foreach($slugs as $slug){
                if($slug->slug == $generated_slug){
                    return response()->json([
                        'msg'=>('Slug already exists')
                    ])->setStatusCode(422);
                }
            }


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
                $standard_title = 'Standard';
                $premium_title = 'premium';
                $standard_regular_charge = $request->standard_regular_charge;
                $standard_discount_charge = $request->standard_discount_charge;
                $premium_regular_charge = $request->premium_regular_charge;
                $premium_discount_charge = $request->premium_discount_charge;
            }

            DB::beginTransaction();
            try {
                $imageName = '';
                $upload_folder = 'project';
                $storage_driver = Storage::getDefaultDriver();

                if ($image = $request->file('image')) {
                    $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                    if (cloudStorageExist() && in_array(get_static_option('storage_driver'), ['s3', 'cloudFlareR2', 'wasabi'])) {
                        add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                    }else{
                        $image->move('assets/uploads/project', $imageName);
                    }
                }

                $project = Project::create([
                    'user_id'=>$user_id,
                    'category_id'=>$request->category,
                    'title'=>$request->project_title,
                    'slug' => Str::slug(purify_html($slug),'-',null),
                    'description'=>$request->project_description,
                    'image'=>$imageName,
                    'basic_title'=>'Basic',
                    'standard_title'=>$standard_title,
                    'premium_title'=>$premium_title,
                    'basic_revision'=>$request->basic_revision ?? 1,
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
                    'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
                ]);
                $project->project_sub_categories()->attach(json_decode($request->subcategory,true));

                $requestData= [];
                foreach(json_decode($request->checkbox_or_numeric_title,true) as $key => $attr){
                    $fallback_value = $attr['checkbox_or_numeric_select'] == 'checkbox' ? "off" : 0;
                    $requestData["checkbox_or_numeric_select"][] = $attr['checkbox_or_numeric_select'];
                    $requestData["check_numeric_title"][] = $attr['check_numeric_title'];
                    $requestData["basic_check_numeric"][] = $attr['basic_check_numeric'] ?? $fallback_value;
                    $requestData["standard_check_numeric"][] = $attr['standard_check_numeric'] ?? $fallback_value;
                    $requestData["premium_check_numeric"][] = $attr['premium_check_numeric'] ?? $fallback_value;
                }

                $data = (array) Validator::make($requestData, [
                    'checkbox_or_numeric_select.*' => 'required|max:100',
                    'check_numeric_title.*' => 'required|max:100',
                    'basic_check_numeric.*' => 'required|max:1000',
                    'standard_check_numeric.*' => 'required',
                    'premium_check_numeric.*' => 'required',
                ])->validated();

                $arr = [];
                foreach($data['check_numeric_title'] as $key => $attr):

                    $arr[] = [
                        'user_id' => $user_id,
                        'create_project_id' => $project->id,
                        'check_numeric_title' => $attr,
                        'basic_check_numeric' => $data["basic_check_numeric"][$key],
                        'standard_check_numeric' => $data["standard_check_numeric"][$key],
                        'premium_check_numeric' => $data["premium_check_numeric"][$key],
                        'type' => $data["checkbox_or_numeric_select"][$key] ?? null,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ];
                endforeach;

                ProjectAttribute::insert($arr);

                DB::commit();
            }catch(Exception $e){

                DB::rollBack();

                if ($request->file('image')) {
                    $delete_img = 'assets/uploads/project/'.$imageName;
                    File::delete($delete_img);
                }

                return response()->json([
                    'msg'=>('Basic check numeric field is required')
                ])->setStatusCode(422);
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
                'type'=>__('Create Project'),
                'message'=>__('A new project has been created'),
            ]);
            return response()->json([
                'msg'=>('Project Successfully Created')
            ]);
        }

    }

    //project details
    public function project_details($id)
    {
        $user_id  = auth('sanctum')->user()->id;
        $find_project = Project::where('id', $id)->where('user_id',$user_id)->first();
        if($find_project){
            $project_details = Project::with([
                'project_category:id,category',
                'project_sub_categories:id,sub_category',
                'project_attributes:id,user_id,create_project_id,type,check_numeric_title,basic_check_numeric,standard_check_numeric,premium_check_numeric'
            ])
                ->withCount('complete_orders','ratings')
                ->withAvg('ratings','rating')
                ->where('id', $id)
                ->where('user_id',$user_id)
                ->first();

            if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $project_details->cloud_link = render_frontend_cloud_image_if_module_exists('project/'.$project_details->image, load_from: $project_details->load_from);
                }

            return response()->json([
                'project_details' => $project_details,
                'project_image_path' => asset('assets/uploads/project/'),
                'freelancer_level' => freelancer_level_api($user_id) ?? '',
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json([
            'msg' => __('Project not found'),
        ]);
    }

    //project update
    public function update_project(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'project_id'=>'required',
                'category'=>'required',
                'project_title'=>'required|min:20|max:100',
                'project_description'=>'required|min:50',
                'slug'=>'required|max:191|unique:projects,slug,'.$request->project_id,
                'basic_revision'=>'required|numeric|integer|max:1000',
                'basic_regular_charge'=>'required|numeric|integer',
                'basic_delivery'=>'required|string|max:191',
                'checkbox_or_numeric_title'=>'required',
            ]);

            $user_id  = auth('sanctum')->user()->id;
            $slug = !empty($request->slug) ? $request->slug : $request->project_title;
            $generated_slug = Str::slug(purify_html($slug));
            $slugs = Project::select('slug')->where('id','!=',$request->project_id)->get();
            $project_details = Project::with('project_attributes')
                ->where('user_id',$user_id)
                ->where('id',$request->project_id)
                ->first();

            if(empty($project_details)){
                return response()->json([
                    'msg' => __('Project not found'),
                ])->setStatusCode(422);
            }

            foreach($slugs as $slug){
                if($slug->slug == $generated_slug){
                    return response()->json([
                        'msg'=>('Slug already exists')
                    ])->setStatusCode(422);
                }
            }

            $standard_title = null;
            $premium_title = null;
            $standard_regular_charge = null;
            $standard_discount_charge = null;
            $premium_regular_charge = null;
            $premium_discount_charge = null;

            if($request->offer_packages_available_or_not == 1){
                $standard_title = 'Standard';
                $premium_title = 'premium';
                $standard_regular_charge = $request->standard_regular_charge;
                $standard_discount_charge = $request->standard_discount_charge;
                $premium_regular_charge = $request->premium_regular_charge;
                $premium_discount_charge = $request->premium_discount_charge;
            }

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
                        $image->move('assets/uploads/project', $imageName);
                    }else{
                        $imageName = $project_details->image;
                    }
                }

                Project::where('id',$request->project_id)->update([
                    'user_id'=>$user_id,
                    'category_id'=>$request->category,
                    'title'=>$request->project_title,
                    'slug' => Str::slug(purify_html($slug),'-',null),
                    'description'=>$request->project_description,
                    'image'=>$imageName,
                    'basic_title'=>'Basic',
                    'standard_title'=>$standard_title,
                    'premium_title'=>$premium_title,
                    'basic_revision'=>$request->basic_revision ?? 1,
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
                    'project_approve_request'=>$project_details->project_approve_request == 1 ? 1 : 0,
                    'offer_packages_available_or_not'=>$request->offer_packages_available_or_not ?? 0,
                ]);
                //update product pivot table data
                $project = Project::find($project_details->id);
                $project->project_sub_categories()->sync(json_decode($request->subcategory,true));
                ProjectAttribute::where('create_project_id',$project_details->id)->delete();

                $requestData= [];
                foreach(json_decode($request->checkbox_or_numeric_title,true) as $key => $attr){
                    $fallback_value = $attr['checkbox_or_numeric_select'] == 'checkbox' ? "off" : 0;
                    $requestData["checkbox_or_numeric_select"][] = $attr['checkbox_or_numeric_select'];
                    $requestData["check_numeric_title"][] = $attr['check_numeric_title'];
                    $requestData["basic_check_numeric"][] = $attr['basic_check_numeric'] ?? $fallback_value;
                    $requestData["standard_check_numeric"][] = $attr['standard_check_numeric'] ?? $fallback_value;
                    $requestData["premium_check_numeric"][] = $attr['premium_check_numeric'] ?? $fallback_value;
                }

                $data = (array) Validator::make($requestData, [
                    'checkbox_or_numeric_select.*' => 'required|max:100',
                    'check_numeric_title.*' => 'required|max:100',
                    'basic_check_numeric.*' => 'required|max:1000',
                    'standard_check_numeric.*' => 'required',
                    'premium_check_numeric.*' => 'required',
                ])->validated();

                $arr = [];
                foreach($data['check_numeric_title'] as $key => $attr):

                    $arr[] = [
                        'user_id' => $user_id,
                        'create_project_id' => $project->id,
                        'check_numeric_title' => $attr,
                        'basic_check_numeric' => $data["basic_check_numeric"][$key],
                        'standard_check_numeric' => $data["standard_check_numeric"][$key],
                        'premium_check_numeric' => $data["premium_check_numeric"][$key],
                        'type' => $data["checkbox_or_numeric_select"][$key] ?? null,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                    ];
                endforeach;

                ProjectAttribute::insert($arr);

                //history create
                $project_id_from_project_history_table = ProjectHistory::where('project_id', $project_details->id)->first();

                if(empty($project_id_from_project_history_table)){
                    ProjectHistory::Create([
                        'project_id'=>$project_details->id,
                        'user_id'=>$project_details->user_id,
                        'reject_count'=>0,
                        'edit_count'=>1,
                    ]);
                }else{
                    ProjectHistory::where('project_id',$project_details->id)->update([
                        'reject_count'=>$project_id_from_project_history_table->edit_count + 1
                    ]);
                }

                DB::commit();
            }catch(Exception $e){

                DB::rollBack();

                if ($request->file('image')) {
                    $delete_img = 'assets/uploads/project/'.$imageName;
                    File::delete($delete_img);
                }

                return response()->json([
                    'msg'=> __('Basic check numeric field is required')
                ])->setStatusCode(422);

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
                'identity'=>$project_details->id,
                'user_id'=>$user_id,
                'type'=>__('Edit Project'),
                'message'=>__('A project has been edited.'),
            ]);
            return response()->json([
                'msg'=>('Project Successfully Updated')
            ]);
        }

    }

    // project delete
    public function delete_project(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $project = Project::withCount('orders')
            ->where('id',$request->project_id)
            ->where('user_id',$user_id)
            ->first();

        if($project){
            if($project?->orders_count >= 1){
                return response()->json(['msg'=>__('Project delete not allowed.')])->setStatusCode(422);
            }
            ProjectAttribute::where('create_project_id',$project->id)->delete();
            ProjectHistory::where('project_id',$project->id)->delete();
            $project->delete();
            return response()->json(['msg'=>__('Project Successfully Deleted')]);
        }
        return response()->json(['msg'=>__('Project not found')])->setStatusCode(422);
    }

    //change project availability status
    public function availability_status(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'project_on_off' => 'required|in:0,1',
        ]);
        $user_id = auth('sanctum')->user()->id;
        $status = $request->project_on_off;

        $project = Project::where('id',$request->project_id)
            ->where('user_id',$user_id)
            ->first();

        if($project){
            Project::where('id',$request->project_id)->update([
                'project_on_off'=>$status,
            ]);
            return response()->json([
                'msg'=> __('Project availability status updated successfully'),
            ]);
        }
        return response()->json([
            'msg'=> __('Project not found'),
        ]);
    }

    //change work availability status
    public function work_availability_status(Request $request)
    {
        $request->validate([
            'check_work_availability' => 'required|in:0,1'
        ]);

        $user_id = auth('sanctum')->user()->id;
        $status = $request->check_work_availability;

        $find_user = User::where('id',$user_id)->first();

        if($find_user){
            User::where('id',$user_id)->update([
                'check_work_availability'=>$status,
            ]);
            return response()->json([
                'status'=> __('Work availability status successfully changed'),
            ]);
        }
        return response()->json([
            'msg'=> __('User not found'),
        ]);

    }
}