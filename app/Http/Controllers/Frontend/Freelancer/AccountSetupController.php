<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserIntroduction;
use App\Models\UserSkill;
use App\Models\UserWork;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Service\Entities\Category;

class AccountSetupController extends Controller
{
    //account setup main page
    public function account_setup(){
        $user_id = Auth::user()->id;
        $user_introduction = UserIntroduction::where('user_id',$user_id)->first();
        $experiences = UserExperience::where('user_id',$user_id)->latest()->get();
        $educations = UserEducation::where('user_id',$user_id)->latest()->get();
        $categories = Category::where('status',1)->take(5)->get();
        $count = Category::count();
        $skip = 5;
        $limit = $count - $skip; // the limit
        $limit = max(0, $limit);
        $more_categories = Category::select(['id','category','slug','image'])->where('status',1)->skip($skip)->take($limit)->get();
        $user_work =  UserWork::where('user_id',$user_id)->first();
        if($user_work){
            $skills_according_to_category =  Skill::select(['id','skill'])->where('category_id',$user_work->category_id)->get();
        }else{
            $skills_according_to_category =  '';
        }
        return view('frontend.user.freelancer.account.account-setup',compact([
            'user_id',
            'user_introduction',
            'experiences',
            'educations',
            'categories',
            'more_categories',
            'user_work',
            'skills_according_to_category'
        ]));
    }

    // add introduction
    public function add_introduction(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);

        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserIntroduction::updateOrCreate(['user_id'=>$user_id],
                [
                'user_id'=>$user_id,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

  // add experience
    public function add_experience(Request $request)
    {
        $request->validate([
            'experience_title'=>'required',
            'short_description'=>'required',
            'organization'=>'required',
            'address'=>'required',
            'start_date'=>'required',
        ]);
        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserExperience::create(
                [
                    'user_id'=>$user_id,
                    'title'=>$request->experience_title,
                    'short_description'=>$request->short_description,
                    'organization'=>$request->organization,
                    'address'=>$request->address,
                    'country_id'=>$request->country_id,
                    'state_id'=>$request->state_id,
                    'start_date'=>$request->start_date,
                    'end_date'=>$request->end_date ?? null,
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // edit experience
    public function update_experience(Request $request)
    {
        $request->validate([
            'experience_title'=>'required',
            'short_description'=>'required',
            'organization'=>'required',
            'address'=>'required',
            'start_date'=>'required',
        ]);
        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserExperience::where('id',$request->id)->update(
                [
                    'user_id'=>$user_id,
                    'title'=>$request->experience_title,
                    'short_description'=>$request->short_description,
                    'organization'=>$request->organization,
                    'address'=>$request->address,
                    'country_id'=>$request->country_id,
                    'state_id'=>$request->state_id,
                    'start_date'=>Carbon::parse($request->start_date),
                    'end_date'=> !empty($request->end_date) ? Carbon::parse($request->end_date) : null,
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // add education
    public function add_education(Request $request)
    {
        $request->validate([
            'institution'=>'required',
            'degree'=>'required',
            'subject'=>'required',
            'start_date'=>'required',
        ]);
        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserEducation::create(
                [
                    'user_id'=>$user_id,
                    'institution'=>$request->institution,
                    'degree'=>$request->degree,
                    'subject'=>$request->subject,
                    'start_date'=>$request->start_date,
                    'end_date'=>$request->end_date ?? null,
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // edit education
    public function update_education(Request $request)
    {
        $request->validate([
            'institution'=>'required',
            'subject'=>'required',
            'degree'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserEducation::where('id',$request->id)->update(
                [
                    'user_id'=>$user_id,
                    'institution'=>$request->institution,
                    'subject'=>$request->subject,
                    'degree'=>$request->degree,
                    'start_date'=>$request->start_date,
                    'start_date'=>Carbon::parse($request->start_date),
                    'end_date'=>Carbon::parse($request->end_date),
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // search category
    public function search_category(Request $request)
    {
        $more_categories = Category::where('status',1)->where('category', 'LIKE', "%". strip_tags($request->string_search) ."%")->get();

        if($more_categories->count() >= 1){
            return view('frontend.user.freelancer.account.work.search-categories', compact('more_categories'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }

    // add work
    public function add_work(Request $request)
    {
        $request->validate([
            'category'=>'required',
        ]);

        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserWork::updateOrCreate(['user_id'=>$user_id],
                [
                    'user_id'=>$user_id,
                    'category_id'=>$request->category,
                    'sub_category_id'=>$request->subcategory,
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // add work
    public function add_skill(Request $request)
    {
        $request->validate([
            'skill'=>'required|max:1000',
        ]);

        if($request->ajax()){
            $user_id = Auth::user()->id;
            UserSkill::updateOrCreate(['user_id'=>$user_id],
                [
                    'user_id'=>$user_id,
                    'skill'=>$request->skill,
                ]);
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // add hourly rate
    public function add_hourly_rate(Request $request)
    {
        $request->validate([
            'hourly_rate'=>'required|numeric|max:1000',
        ]);

        if($request->ajax()){
            $user_id = Auth::user()->id;
            User::where('id',$user_id)->update([
                    'hourly_rate'=>$request->hourly_rate,
                ]);
            if(!moduleExists('HourlyJob')){
                User::where('id',$user_id)->update([
                    'hourly_rate'=> 0,
                ]);
            }
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // upload profile photo
    public function upload_profile_photo(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $user_image = User::where('id',$user_id)->first();
        $delete_old_img =  'assets/uploads/profile/'.$user_image->image;

        $upload_folder = 'profile';
        $storage_driver = Storage::getDefaultDriver();

        if ($image = $request->file('profile_image')) {
            if(file_exists($delete_old_img)){
                File::delete($delete_old_img);
            }
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $resize_full_image = Image::make($request->profile_image)
                ->resize(80, 80);

            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if(!empty($user_image)) {
                    // Get the current image path from the database
                    $currentImagePath = $user_image->image;
                    // Delete the old image if it exists
                    if ($currentImagePath) {
                        delete_frontend_cloud_image_if_module_exists('profile/' . $currentImagePath);
                    }
                }
                add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
            }else{
                $resize_full_image->save('assets/uploads/profile' .'/'. $imageName);
            }


        }else{
            $imageName = $user_image->image;
        }

        User::where('id',$user_id)->update([
            'image'=>$imageName,
            'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
        ]);

        return response()->json([
            'status'=>'uploaded',
        ]);
    }

    //congrats
    public function congrats(){
        return view('frontend.user.freelancer.account.congrats');
    }

}
