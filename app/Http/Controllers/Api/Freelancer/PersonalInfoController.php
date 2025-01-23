<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalInfoDisplayResource;
use App\Http\Resources\PersonalInfoUpdateResource;
use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Rating;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserEarning;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserSkill;
use App\Models\UserWork;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\PromoteFreelancer\Entities\PromotionProjectList;

class PersonalInfoController extends Controller
{
    public function personal_info()
    {
        $personal_info = User::with('user_country:id,country','user_state:id,state','user_city:id,city')
            ->select('id', 'first_name','last_name', 'email', 'country_id', 'state_id','city_id','experience_level','phone','image','load_from')
            ->where('id',auth('sanctum')->user()->id)
            ->first();

        if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            $personal_info->cloud_link = render_frontend_cloud_image_if_module_exists('profile/'.$personal_info->image, load_from: $personal_info->load_from);
            return response()->json([
                'data'=> $personal_info,
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }else{
            return new PersonalInfoDisplayResource($personal_info);
        }

        return response()->json([
            'msg'=> __('No info found'),
        ]);
    }

    public function personal_info_update(Request $request)
    {
        $request->validate(
            [
                'first_name'=>'required|min:2|max:50',
                'last_name'=>'required|min:2|max:50',
                'email'=>'required|email|unique:users,email,'.auth('sanctum')->user()->id,
                'country'=>'required',
                'state'=>'required',
                'city'=>'required',
                'level'=>'required',
                'phone'=>'required',
            ],
            [
                'first_name.required'=>'First name is required',
                'last_name.required'=>'Last name is required',
                'country_id.required'=>'Country is required',
                'state_id.required'=>'State is required',
                'city_id.required'=>'City is required',
                'level.required'=>'Experience level is required',
                'phone.required'=>'Phone number is required',
            ]);

            $user = User::where('id',auth('sanctum')->user()->id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'country_id'=>$request->country,
                'state_id'=>$request->state,
                'city_id'=>$request->city,
                'experience_level'=>$request->level,
                'phone'=>$request->phone,
            ]);

            if($user){
                return new PersonalInfoUpdateResource($user);
            }
            return response()->json([
                'msg'=> __('Update failed'),
            ]);
    }

    public function profile_image_update(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $user_image = User::where('id',$user_id)->first();
        $delete_old_img =  'assets/uploads/profile/'.$user_image->image;

        $upload_folder = 'profile';

        if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            if ($image = $request->file('image')) {
                $request->validate(
                    ['image'=>'required|mimes:jpg,jpeg,png,gif,svg|max:1024'],
                    ['image.required'=>'Image is required']
                );
                $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                // Get the current image path from the database
                $currentImagePath = $user_image->image;
                // Delete the old image if it exists
                if ($currentImagePath) {
                    delete_frontend_cloud_image_if_module_exists('profile/'.$currentImagePath);
                }
                add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
            }else{
                $imageName = $user_image->image;
            }
        }else{
            if ($image = $request->file('image')) {
                $request->validate(
                    ['image'=>'required|mimes:jpg,jpeg,png,gif,svg|max:1024'],
                    ['image.required'=>'Image is required']
                );
                if(file_exists($delete_old_img)){
                    File::delete($delete_old_img);
                }
                $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move('assets/uploads/profile', $imageName);
            }else{
                $imageName = $user_image->image;
            }
        }

        if($imageName){
            User::where('id',$user_id)->update(['image'=>$imageName]);
            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $storage_driver = Storage::getDefaultDriver();
                User::where('id',$user_id)->update(['load_from'=>in_array($storage_driver,['CustomUploader']) ? 0 : 1,]);
            }
            return response()->json(['msg' => __('Profile photo successfully updated')]);
        }
        return response()->json(['msg' => __('Profile photo updated failed')]);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|min:6|max:191',
            'new_password' => 'required|min:6|max:191',
            'confirm_new_password' => 'required|min:6|max:191',
        ]);
        $user = User::select(['id','password'])->where('id',auth('sanctum')->user()->id)->first();

        if (Hash::check($request->current_password, $user->password)) {
            if ($request->new_password == $request->confirm_new_password) {
                User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
                return response()->json(['msg'=> __('Password successfully updated')]);
            }
            return response()->json(['status'=>'Password not match']);
        }else{
            return response()->json(['msg'=>'Current password is wrong']);
        }
    }

    public function profile_details()
    {
        $user = User::with('user_introduction')
            ->select(['id','image','hourly_rate','first_name','last_name','username','country_id','state_id','check_work_availability','load_from'])
            ->where('id',auth('sanctum')->user()->id)
            ->first();

        if($user) {
            $user_work = UserWork::where('user_id', $user->id)->first();
            $total_earning = UserEarning::where('user_id', $user->id)->first();
            $complete_orders = Order::select('id', 'identity', 'status')->where('freelancer_id', $user->id)->where('status', 3)->latest()->get();

            $skills_according_to_category = isset($user_work) ? Skill::select(['id', 'skill'])->where('category_id', $user_work->category_id)->get() : '';

            $skills = UserSkill::select('skill')->where('user_id', $user->id)->first()->skill ?? '';
            $portfolios = Portfolio::where('username', $user->username)->latest()->get();
            $educations = UserEducation::where('user_id', $user->id)->latest()->get();
            $experiences = UserExperience::where('user_id', $user->id)->latest()->get();

            $projects = Project::select(['id','title','status','image','basic_regular_charge','basic_discount_charge','basic_delivery','slug','project_on_off','is_pro','pro_expire_date','load_from'])
                ->where('user_id', $user->id)->withAvg(['ratings' => function ($query){
                $query->where('sender_id', 1);
            }],'rating')
                ->latest()->get();


            $avg_rating = freelancer_rating($user->id, 'header');

            $count = 0;
            $rating_count = 0;
            foreach ($complete_orders as $order) {
                $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
                if ($rating) {
                    $count = $count + 1;
                    $rating_count = $rating_count + 1;
                }
            }

            //timezone
            $timezone = '';
            if(!empty($user?->user_state->timezone)){
                date_default_timezone_set(optional($user->user_state)->timezone ?? '');
                $timezone = date('h:i:a');
            }

            //freelancer reviews
            $review_rating = [];
            $review_feedback= [];
            $review_project= [];
            foreach($complete_orders as $key => $order) {
                $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
                if(!empty($rating)){
                    $review_rating[] = $rating->rating;
                    $review_feedback[] = $rating->review_feedback;
                    $review_project[] = $rating->order?->project?->title ?? $rating->order?->job?->title;
                }
            }

            if($user->image){
                $user->freelancer_cloud_image = render_frontend_cloud_image_if_module_exists('profile/'.$user->image, load_from: $user->load_from);
            }else{
                $user->freelancer_cloud_image = null;
            }

            if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $projects->transform(function ($project) {
                    $project->project_cloud_image = render_frontend_cloud_image_if_module_exists('project/'.$project->image, load_from: $project->load_from);
                    return $project;
                });
            }

            if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $portfolios->transform(function ($portfolio) {
                    $portfolio->portfolio_cloud_image = render_frontend_cloud_image_if_module_exists('portfolio/'.$portfolio->image, load_from: $portfolio->load_from);
                    return $portfolio;
                });
            }

            if(moduleExists('PromoteFreelancer')){
                if(!empty($user)){
                    $current_date = \Carbon\Carbon::now()->toDateTimeString();
                    $is_promoted = PromotionProjectList::where('identity',$user->id)
                        ->where('type','profile')
                        ->where('expire_date','>',$current_date)
                        ->where('payment_status','complete')
                        ->first();
                }
            }

            return response()->json([
                'username' => $user->username,
                'user_country' => $user?->user_country?->country,
                'user_state' => $user?->user_state?->state,
                'profile_image_path' => asset('assets/uploads/profile/'),
                'avg_rating' => $avg_rating,
                'total_rating' => $rating_count,
                'review_rating' => $review_rating,
                'review_feedback' => $review_feedback,
                'review_project' => $review_project,
                'skills_according_to_category' => $skills_according_to_category,
                'portfolios' => $portfolios,
                'portfolio_path' => asset('assets/uploads/portfolio/'),
                'skills' => $skills,
                'educations' => $educations,
                'experiences' => $experiences,
                'projects' => $projects,
                'project_path' => asset('assets/uploads/project/'),
                'user' => $user,
                'is_profile_promoted'=> !empty($is_promoted) ? true : false,
                'total_earning' => $total_earning,
                'complete_orders' => $complete_orders,
                'timezone' => $timezone,
                'freelancer_level' => freelancer_level_api($user->id) ?? '',
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }else{
            return response()->json(['msg' => __('Freelancer not found')]);
        }
    }

    //account delete
    public function account_delete()
    {
        User::find(auth('sanctum')->user()->id)->delete();
        return response()->json(['msg' => __('Account Delete Success')]);
    }

    public function update_firebase_token(Request $request)
    {
        $data = $request->validate([
            "token" => "required|string"
        ]);

        User::where("id", auth('sanctum')->user()->id)->update([
            "firebase_device_token" => $data["token"]
        ]);

        return response()->json([
            "msg" => __("Successfully updated firebase token."),
            "status" => true
        ]);
    }
}
