<?php

namespace App\Http\Controllers\Api\Client;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonalInfoController extends Controller
{
    public function personal_info()
    {
        $personal_info = User::with('user_country:id,country','user_state:id,state','user_city:id,city')
            ->select('id', 'first_name','last_name', 'email', 'country_id', 'state_id','city_id','experience_level','phone','image','load_from')
            ->where('id',auth('sanctum')->user()->id)
            ->first();

        if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            $personal_info->freelancer_cloud_image = render_frontend_cloud_image_if_module_exists('profile/'.$personal_info->image, load_from: $personal_info->load_from);
            return response()->json([
                'data'=> $personal_info,
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }else{
            return new PersonalInfoDisplayResource($personal_info);
        }
        return response()->json([
            'msg'=> __('No info found'),
            'storage_driver' => Storage::getDefaultDriver() ?? '',
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
            return response()->json(['status'=>'Password not match'])->setStatusCode(422);
        }else{
            return response()->json(['msg'=>'Current password is wrong'])->setStatusCode(422);
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
