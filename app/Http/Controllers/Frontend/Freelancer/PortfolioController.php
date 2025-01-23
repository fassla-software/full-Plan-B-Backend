<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Portfolio;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserIntroduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PortfolioController extends Controller
{
    //add portfolio
    public function add_portfolio(Request $request)
    {
        $request->validate(
            [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024',
                'portfolio_title'=>'required|string|min:10|max:60|unique:portfolios,title',
                'portfolio_description'=>'required|string|min:50|max:150',
            ],
            [
                'image.required'=>'Portfolio image is required',
                'portfolio_title.required'=>'Portfolio title is required',
                'portfolio_description.required'=>'Portfolio description is required',
            ]
        );

            if($request->ajax())
            {
                $imageName = '';
                if ($image = $request->file('image')) {
                    $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                    $resize_full_image = Image::make($request->image)
                        ->resize(590, 440);

                    $upload_folder = 'portfolio';
                    $storage_driver = Storage::getDefaultDriver();
                    if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                        add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                    }else{
                        $resize_full_image->save('assets/uploads/portfolio' .'/'. $imageName);
                    }
                }
                Portfolio::create([
                    'user_id'=>Auth::guard('web')->user()->id,
                    'username'=>Auth::guard('web')->user()->username,
                    'title'=>$request->portfolio_title,
                    'description'=>$request->portfolio_description,
                    'image'=>$imageName,
                    'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
                ]);

                return response()->json([
                    'status'=>'success',
                ]);
            }
    }

    //edit portfolio
    public function edit_portfolio(Request $request)
    {
        $request->validate(
            [
                'edit_portfolio_title'=>'required|string|min:10|max:60|unique:portfolios,title,'.$request->edit_portfolio_id,
                'edit_portfolio_description'=>'required|string|min:50|max:150',
            ],
            [
                'edit_portfolio_title.required'=>'Portfolio title is required',
                'edit_portfolio_description.required'=>'Portfolio description is required',
            ]
        );

        $portfolio_image= Portfolio::select('image')->where('id',$request->edit_portfolio_id)->first();
        $delete_old_img =  'assets/uploads/portfolio/'.$portfolio_image->image;

        if($request->ajax())
        {
            $imageName = '';
            $upload_folder = 'portfolio';

            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if ($image = $request->file('edit_image')) {
                    $request->validate(
                        ['edit_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024'],
                        ['edit_image.required'=>'Portfolio image is required']
                    );
                    $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();

                    // Get the current image path from the database
                    $currentImagePath = $portfolio_image->image;
                    // Delete the old image if it exists
                    if ($currentImagePath) {
                        delete_frontend_cloud_image_if_module_exists('portfolio/'.$currentImagePath);
                    }
                    add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                }else{
                    $imageName = $portfolio_image->image;
                }
            }else{
                if ($image = $request->file('edit_image')) {
                    $request->validate(
                        ['edit_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024'],
                        ['edit_image.required'=>'Portfolio image is required']
                    );

                    if(file_exists($delete_old_img)){
                        File::delete($delete_old_img);
                    }
                    $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
                    $resize_full_image = Image::make($request->edit_image)
                        ->resize(590, 440);
                    $resize_full_image->save('assets/uploads/portfolio' .'/'. $imageName);

                }else{
                    $imageName = $portfolio_image->image;
                }
            }


            Portfolio::where('id',$request->edit_portfolio_id)->update([
                'user_id'=>Auth::guard('web')->user()->id,
                'username'=>Auth::guard('web')->user()->username,
                'title'=>$request->edit_portfolio_title,
                'description'=>$request->edit_portfolio_description,
                'image'=>$imageName,
            ]);

            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //delete portfolio
    public function delete_portfolio(Request $request)
    {
        if($request->ajax()) {
            $portfolio = Portfolio::find($request->id);
            if ($portfolio) {
                if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                    // Get the current image path from the database
                    $currentImagePath = $portfolio->image;
                    // Delete the old image if it exists
                    if ($currentImagePath) {
                        delete_frontend_cloud_image_if_module_exists('portfolio/' . $currentImagePath);
                    }
                } else {
                    $delete_old_img =  'assets/uploads/portfolio/'.$portfolio->image;
                    if(file_exists($delete_old_img)){
                        File::delete($delete_old_img);
                    }
                }
                $portfolio->delete();
            }
            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //delete education
    public function delete_education(Request $request)
    {
        if($request->ajax()){
            UserEducation::find($request->id)->delete();
            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //delete experience
    public function delete_experience(Request $request)
    {
        if($request->ajax()){
            UserExperience::find($request->id)->delete();
            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //change project availability status
    public function availability_status(Request $request)
    {
        if($request->ajax()){
            $status = $request->project_on_off == 1 ? 0 :1;
            Project::where('id',$request->id)->update([
                'project_on_off'=>$status,
            ]);
            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //change work availability status
    public function work_availability_status(Request $request)
    {
        if($request->ajax()){
            $status = $request->check_work_availability == 1 ? 0 :1;
            User::where('id',$request->user_id)->update([
                'check_work_availability'=>$status,
            ]);
            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //update profile details
    public function profile_details_update(Request $request)
    {
        $request->validate(
            [
                'first_name' => 'required|min:2|max:30',
                'last_name' => 'required|min:2|max:30',
                'title'=>'required|string|min:10|max:60',
                'description'=>'required|string|min:50|max:150',
                'country_id'=>'required',
            ],
            [
                'first_name.required'=>'First name is required',
                'last_name.required'=>'Last name is required',
                'title.required'=>'Professional title is required',
                'description.required'=>'Professional description is required',
                'country_id.required'=>'Country is required',
            ]
        );

        if($request->ajax())
        {
            $user_id = Auth::guard('web')->user()->id;
            User::where('id',$user_id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'country_id'=>$request->country_id,
                'state_id'=>$request->state_id,
            ]);

            UserIntroduction::updateOrCreate(['user_id'=>$user_id],
            [
                'user_id'=>$user_id,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);

            return response()->json([
                'status'=>'success',
            ]);
        }
    }

    //update profile details hourly rate
    public function profile_details_hourly_rate_update(Request $request)
    {
        $request->validate(
            [
                'hourly_rate' => 'required|numeric|min:1|max:300',
            ],
            [
                'hourly_rate.required'=>'Price is required',
            ]
        );

        if($request->ajax())
        {
            User::where('id',Auth::guard('web')->user()->id)->update([
                'hourly_rate'=>$request->hourly_rate,
            ]);
            return response()->json([
                'status'=>'success',
            ]);
        }
    }


}
