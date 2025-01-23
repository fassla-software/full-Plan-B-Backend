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
use App\Models\HeavyEquipment;

class ProjectController extends Controller
{
    public function get_view()
{
    return view('your_blade_view');
}

    
    public function create_project(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'category'=>'required',
                'size'=>'required|string|max:191',
                'model'=>'required|string|max:191',
                'year_of_manufacture'=>'required|numeric|digits:4',
                'moves_on'=>'required|string|max:191',
                'current_equipment_location'=>'required|string|max:191',
                'data_certificate_image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                'driver_license_front_image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                'driver_license_back_image'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                'additional_equipment_images'=>'required|mimes:jpg,jpeg,png,bmp,tiff,svg|max:5120',
                'special_rental_conditions'=>'nullable|string',
                'blade_width'=>'nullable|numeric',
                'blade_width_near_digging_arm'=>'nullable|numeric',
                'engine_power'=>'nullable|numeric',
                'milling_blade_width'=>'nullable|numeric',
                'sprinkler_system_type'=>'nullable|string|max:191',
                'tank_capacity'=>'nullable|numeric',
                'panda_width'=>'nullable|numeric',
                'has_bitumen_temp_gauge'=>'nullable|boolean',
                'has_bitumen_level_gauge'=>'nullable|boolean',
                'paving_range'=>'nullable|string|max:191',
                'max_equipment_load'=>'nullable|numeric',
                'boom_length'=>'nullable|numeric',
                'load_at_max_boom_height'=>'nullable|numeric',
                'load_at_max_horizontal_boom_extension'=>'nullable|numeric',
                'max_lifting_point'=>'nullable|numeric',
                'attachments'=>'nullable|string',
                'has_tank_discharge_pump'=>'nullable|boolean',
                'has_band_sprinkler_bar'=>'nullable|boolean',
                'has_discharge_pump_with_liters_meter'=>'nullable|boolean'
            ]);
    
            $user_id = auth()->user()->id;
    
            DB::beginTransaction();
            try {
                $upload_folder = 'heavy_equipment';
                $storage_driver = Storage::getDefaultDriver();
                $imageNames = [];
    
                $imageFields = [
                    'data_certificate_image', 'driver_license_front_image', 
                    'driver_license_back_image', 'additional_equipment_images'
                ];
    
                foreach ($imageFields as $field) {
                    if ($image = $request->file($field)) {
                        $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $imageNames[$field] = $imageName;
    
                        if (cloudStorageExist() && in_array(get_static_option('storage_driver'), ['s3', 'cloudFlareR2', 'wasabi'])) {
                            add_frontend_cloud_image_if_module_exists($upload_folder, $image, $imageName,'public');
                        } else {
                            $image->move('assets/uploads/heavy_equipment', $imageName);
                        }
                    } else {
                        $imageNames[$field] = '';
                    }
                }
    
                HeavyEquipment::create([
                    'category_id'=>$request->category,
                    'size'=>$request->size,
                    'model'=>$request->model,
                    'year_of_manufacture'=>$request->year_of_manufacture,
                    'moves_on'=>$request->moves_on,
                    'current_equipment_location'=>$request->current_equipment_location,
                    'data_certificate_image'=>$imageNames['data_certificate_image'],
                    'driver_license_front_image'=>$imageNames['driver_license_front_image'],
                    'driver_license_back_image'=>$imageNames['driver_license_back_image'],
                    'additional_equipment_images'=>$imageNames['additional_equipment_images'],
                    'special_rental_conditions'=>$request->special_rental_conditions,
                    'blade_width'=>$request->blade_width,
                    'blade_width_near_digging_arm'=>$request->blade_width_near_digging_arm,
                    'engine_power'=>$request->engine_power,
                    'milling_blade_width'=>$request->milling_blade_width,
                    'sprinkler_system_type'=>$request->sprinkler_system_type,
                    'tank_capacity'=>$request->tank_capacity,
                    'panda_width'=>$request->panda_width,
                    'has_bitumen_temp_gauge'=>$request->has_bitumen_temp_gauge,
                    'has_bitumen_level_gauge'=>$request->has_bitumen_level_gauge,
                    'paving_range'=>$request->paving_range,
                    'max_equipment_load'=>$request->max_equipment_load,
                    'boom_length'=>$request->boom_length,
                    'load_at_max_boom_height'=>$request->load_at_max_boom_height,
                    'load_at_max_horizontal_boom_extension'=>$request->load_at_max_horizontal_boom_extension,
                    'max_lifting_point'=>$request->max_lifting_point,
                    'attachments'=>$request->attachments,
                    'has_tank_discharge_pump'=>$request->has_tank_discharge_pump,
                    'has_band_sprinkler_bar'=>$request->has_band_sprinkler_bar,
                    'has_discharge_pump_with_liters_meter'=>$request->has_discharge_pump_with_liters_meter,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
    
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                foreach ($imageNames as $imageName) {
                    if (!empty($imageName)) {
                        File::delete('assets/uploads/heavy_equipment/' . $imageName);
                    }
                }
                return response()->json([
                    'msg' => 'Error occurred during project creation'
                ])->setStatusCode(422);
            }
    
            // try {
            //     $message = get_static_option('project_create_email_message') ?? 'A new project is just created.';
            //     $message = str_replace(["@project_id"], [$project->id], $message);
            //     Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
            //         'subject' => get_static_option('project_create_email_subject') ?? 'Project Create Email',
            //         'message' => $message
            //     ]));
            // } catch (Exception $e) {}
    
            // AdminNotification::create([
            //     'identity' => $project->id,
            //     'user_id' => $user_id,
            //     'type' => 'Create Project',
            //     'message' => 'A new project has been created',
            // ]);
    
            return response()->json([
                'msg' => 'Project Successfully Created'
            ]);
        }
    }
    
    
}