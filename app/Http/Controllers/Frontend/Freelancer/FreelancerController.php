<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Feedback;
use App\Models\IdentityVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class FreelancerController extends Controller
{
    // freelancer profile settings page
    public function profile()
    {
        return view('frontend.user.freelancer.profile.profile-settings');
    }

    // edit freelancer profile
    public function edit_profile(Request $request)
    {
        $request->validate(
            [
                'first_name'=>'required|min:1|max:50',
                'last_name'=>'required|min:1|max:50',
                'email'=>'required|email|unique:users,email,'.Auth::guard('web')->user()->id,
                'country'=>'required',
                'level'=>'required',
            ],
            [
                'first_name.required'=>'First name is required',
                'last_name.required'=>'Last name is required',
                'country_id.required'=>'Country is required',
                'level.required'=>'Experience level is required',
            ]);

        if($request->ajax()){
            User::where('id',Auth::guard('web')->user()->id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'country_id'=>$request->country,
                'state_id'=>$request->state,
                'city_id'=>$request->city,
                'experience_level'=>$request->level,
            ]);
            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Edit profile','Freelancer');
            }
            return response()->json([
                'status'=>'ok',
            ]);
        }
    }

    // edit freelancer profile photo
    public function edit_profile_photo(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
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

                $resize_full_image = Image::make($request->image)
                    ->resize(80, 80);
                $resize_full_image->save('assets/uploads/profile' .'/'. $imageName);

            }else{
                $imageName = $user_image->image;
            }
        }

        if($request->ajax()){
            User::where('id',$user_id)->update(['image'=>$imageName]);
            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                $storage_driver = Storage::getDefaultDriver();
                User::where('id',$user_id)->update(['load_from'=>in_array($storage_driver,['CustomUploader']) ? 0 : 1,]);
            }
            return response()->json(['status'=>'ok']);
        }
    }

    // freelancer identity verification
    public function identity_verification(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        if($request->isMethod('post')){

            $request->validate([
                'country'=>'required',
                'address'=>'required|max:191',
                'national_id_number'=>'required|max:255',
                'front_image'=>'required|image|mimes:jpeg,png,jpg|max:5120',
                'back_image'=>'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            if(moduleExists('CoinPaymentGateway')){
            }else{
                $request->validate([
                    'state'=>'required',
                    'zipcode'=>'required|max:191',
                ]);
            }

            $verification_image = IdentityVerification::where('user_id',$user_id)->first();
            $delete_front_img = '';
            $delete_back_img = '';
            $upload_folder = 'verification';
            $storage_driver = Storage::getDefaultDriver();

            if(!empty($verification_image)){
                $delete_front_img =  'assets/uploads/verification/'.$verification_image->front_image;
                $delete_back_img =  'assets/uploads/verification/'.$verification_image->back_image;
            }

            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if ($image = $request->file('front_image')) {
                    $front_image_name = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                    if(!empty($verification_image)) {
                        // Get the current image path from the database
                        $currentImagePath = $verification_image->front_image;
                        // Delete the old image if it exists
                        if ($currentImagePath) {
                            delete_frontend_cloud_image_if_module_exists('verification/' . $currentImagePath);
                        }
                    }
                    add_frontend_cloud_image_if_module_exists($upload_folder, $image, $front_image_name,'public');
                }else{
                    $front_image_name = $verification_image->front_image;
                }
            }else{
                if ($image = $request->file('front_image')) {
                    if(file_exists($delete_front_img)){
                        File::delete($delete_front_img);
                    }
                    $front_image_name = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                    $resize_full_image = Image::make($request->front_image)
                        ->resize(500, 300);
                    $resize_full_image->save('assets/uploads/verification' .'/'. $front_image_name);
                }else{
                    $front_image_name = $verification_image->front_image;
                }
            }


            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if ($image = $request->file('back_image')) {
                    $back_image_name = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                    if(!empty($verification_image)) {
                        // Get the current image path from the database
                        $currentImagePath = $verification_image->back_image;
                        // Delete the old image if it exists
                        if ($currentImagePath) {
                            delete_frontend_cloud_image_if_module_exists('verification/' . $currentImagePath);
                        }
                    }
                    add_frontend_cloud_image_if_module_exists($upload_folder, $image, $back_image_name,'public');
                }else{
                    $back_image_name = $verification_image->back_image;
                }
            }else{
                if ($image = $request->file('back_image')) {
                    if(file_exists($delete_back_img)){
                        File::delete($delete_back_img);
                    }
                    $back_image_name= time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                    $resize_full_image = Image::make($request->back_image)
                        ->resize(500, 300);
                    $resize_full_image->save('assets/uploads/verification' .'/'. $back_image_name);
                }else{
                    $back_image_name = $verification_image->back_image;
                }
            }


            IdentityVerification::updateOrCreate(
                ['user_id'=> $user_id],
                [
                    'user_id'=>$user_id,
                    'verify_by'=>$request->verify_by,
                    'country_id'=>$request->country,
                    'state_id'=>$request->state ?? 0,
                    'city_id'=>$request->city ?? 0,
                    'address'=>$request->address,
                    'zipcode'=>$request->zipcode ?? 0,
                    'national_id_number'=>$request->national_id_number,
                    'front_image'=>$front_image_name,
                    'back_image'=>$back_image_name,
                    'status'=>null,
                    'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, // 0=local 1=cloud
                ]
            );
            try {
                $message = get_static_option('user_identity_verify_message') ?? "<p>{{ __('Hello')}},</p></p>{{ __('You have a new request for user identity verification')}}</p>";
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('user_identity_verify_subject') ?? __('User Identity Verify Email'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}
            return response()->json(['status'=>'success']);
        }
        $user_identity = IdentityVerification::where('user_id',$user_id)->first();
        return view('frontend.user.freelancer.identity.verification',compact('user_identity'));
    }

    // check password
    public function check_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $current_password = User::select('password')->where('id',Auth::user()->id)->first();
            if (Hash::check($request->current_password, $current_password->password)) {
                return response()->json([
                    'status'=>'match',
                    'msg'=>__('Current password match'),
                ]);
            }else{
                return response()->json([
                    'msg'=>__('Current password is wrong'),
                ]);
            }
        }
    }

    // password change
    public function change_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'current_password' => 'required|min:6|max:191',
                'new_password' => 'required|min:6|max:191',
                'confirm_new_password' => 'required|min:6|max:191',
            ]);
            $user = User::select(['id','password'])->where('id',Auth::user()->id)->first();

            if (Hash::check($request->current_password, $user->password)) {
                if ($request->new_password == $request->confirm_new_password) {
                    //security manage
                    if(moduleExists('SecurityManage')){
                        LogActivity::addToLog('Password change','Freelancer');
                    }
                    User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
                    return response()->json(['status'=>'success']);
                }
                return response()->json(['status'=>'not_match']);
            }
            return response()->json(['status'=>'current_pass_wrong']);
        }
        return view('frontend.user.freelancer.password.password');
    }

    // submit feedback
    public function submit_feedback(Request $request)
    {
        $request->validate([
            'title' => 'required|min:6|max:191',
            'description' => 'required|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

       $feedback = Feedback::updateOrCreate(
           [
               'user_id'=> Auth::guard('web')->user()->id
           ],
            [
            'user_id' =>Auth::guard('web')->user()->id,
            'title' =>$request->title,
            'description' =>$request->description,
            'rating' =>$request->rating,
            'status' =>0,
        ]);

        return !empty($feedback) ? response()->json(['status'=>'success']) : response()->json(['status'=>'failed','msg' => __('Something Went wrong')]);

    }

    //account delete
    public function account_delete(Request $request)
    {
        if ($request->isMethod('post')) {
            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Account delete','Freelancer');
            }
            User::find(Auth::user()->id)->delete();
            Auth::guard('web')->logout();
            return redirect()->route('homepage');
        }
        return view('frontend.user.freelancer.profile.account-delete');
    }

    // freelancer logout
    public function logout()
    {
        //security manage
        if(moduleExists('SecurityManage')){
            LogActivity::addToLog('User logout','Freelancer');
        }
        if(Session::has('user_role')){Session::forget('user_role');}
        Auth::guard('web')->logout();
        (new Authenticator(request()))->logout();
        return redirect()->route('homepage');
    }
}
