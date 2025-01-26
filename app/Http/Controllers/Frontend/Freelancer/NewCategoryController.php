<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\HeavyEquipmentRequest;
use App\Http\Requests\CategoryRequest\SiteServiceCarRequest;
use App\Models\HeavyEquipment;
use App\Models\SiteServiceCar;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NewCategoryController extends Controller
{
    use ImageUploadTrait;

    public function showForm($subCategory)
    {
        $views = [
            'heavy_equipment' => 'forms.heavy_equipment',
            'site_service_car' => 'forms.site_service_car',
            // Add other sub-categories here
        ];

        if (!isset($views[$subCategory])) {
            abort(404, 'Sub-category not found');
        }

        return view($views[$subCategory]);
    }

    public function storeData(Request $request, $subCategory)
    {
        DB::beginTransaction();
    
        try {
            // Base validation rules
            $validationRules = [
                'category_id' => 'required|integer',
                'size' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year_of_manufacture' => 'nullable|integer',
                'moves_on' => 'nullable|string|max:255',
                'current_equipment_location' => 'nullable|string|max:255',
                'special_rental_conditions' => 'nullable|string',
            ];
    
            // Additional validation for specific subcategories
            if ($subCategory === 'heavy_equipment') {
                $validationRules = array_merge($validationRules, [
                    'data_certificate_image' => 'nullable|file|mimes:jpg,jpeg,png',
                    'driver_license_front_image' => 'nullable|file|mimes:jpg,jpeg,png',
                    'driver_license_back_image' => 'nullable|file|mimes:jpg,jpeg,png',
                ]);
            } elseif ($subCategory === 'site_service_cars') {
                $validationRules = array_merge($validationRules, [
                    'image' => 'nullable|file|mimes:jpg,jpeg,png',
                ]);
            } else {
                throw new \Exception('Invalid sub-category provided.');
            }
    
            // Validate the request
            $validatedData = $request->validate($validationRules);
    
            // Debugging: Log validated data
            \Log::info("Validated Data:", $validatedData);
    
            // Handle file uploads
            $fileFields = ['data_certificate_image', 'driver_license_front_image', 'driver_license_back_image', 'image'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $validatedData[$field] = $request->file($field)->store("uploads/{$subCategory}", 'public');
                }
            }
    
            // Debugging: Log after file uploads
            \Log::info("Data after file uploads:", $validatedData);
    
            // Save data to the respective table
            if ($subCategory === 'heavy_equipment') {
                $saved = \App\Models\HeavyEquipment::create($validatedData);
            } elseif ($subCategory === 'site_service_cars') {
                $saved = \App\Models\SiteServiceCar::create($validatedData);
            }
    
            // Debugging: Log if the model was saved successfully
            if ($saved) {
                \Log::info("Data saved successfully for {$subCategory}: ", $saved->toArray());
            } else {
                throw new \Exception("Failed to save data for {$subCategory}.");
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', ucfirst(str_replace('_', ' ', $subCategory)) . ' data saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            // Debugging: Log the error
            \Log::error("Error saving data for {$subCategory}: " . $e->getMessage());
    
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    
}
