<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\HeavyEquipmentRequest;
use App\Http\Requests\CategoryRequest\VehicleRentRequest;
use App\Http\Resources\NewCategoryResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Service\Entities\Category;

class NewCategoryController extends Controller
{
    use ImageUploadTrait;

   public function storeData(Request $request, $subCategory, $subSubCategory)
{
    // Start transaction
    DB::beginTransaction();

    try {
        // Decode 'additional_equipment_images' to an array (if it's a JSON string)
        if (is_string($request['additional_equipment_images'])) {
            // Only decode if it's a string (JSON)
            $request['additional_equipment_images'] = json_decode($request['additional_equipment_images'], true);
        }


        // Validate data using the specific request class
        $requests = [
            MachineType::heavyEquipment->value => HeavyEquipmentRequest::class,
            MachineType::vehicleRental->value => VehicleRentRequest::class,
        ];

        if (!isset($requests[$subCategory])) {
            return response()->json(['error' => 'Sub-category not found'], 404);
        }
        $request['equipment_type'] = $subSubCategory;
        $request['name'] = ucfirst($subSubCategory);
        // Resolve and validate using the specific request class
        $validatedData = app($requests[$subCategory])->validated();
        $validatedData['user_id'] = auth('sanctum')->user()->id;

        // Handle image fields
        $imageFields = [
            'data_certificate_image',
            'driver_license_front_image',
            'driver_license_back_image',
            'additional_equipment_images',
            'tractor_license_front_image',
            'tractor_license_back_image',
            'flatbed_license_front_image',
            'flatbed_license_back_image',
        ];

        // Process image fields
        foreach ($imageFields as $field) {
            if (!empty($validatedData[$field])) {
                if (is_array($validatedData[$field])) {
                    // If it's an array of images (for example: additional_equipment_images)
                    $validatedData[$field] = array_map(function ($url) {
                        return basename($url);  // Extract only the file name
                    }, $validatedData[$field]);

                    // Encode the images back to JSON for storage
                    $validatedData[$field] = json_encode($validatedData[$field]);
                } else {
                    // If it's a single image URL
                    $validatedData[$field] = basename($validatedData[$field]);
                }
            }
        }

        // Map sub-category to model
        $models = [
            MachineType::heavyEquipment->value => \App\Models\HeavyEquipment::class,
            MachineType::vehicleRental->value => \App\Models\VehicleRent::class,
        ];

        $model = $models[$subCategory];
        $model::create($validatedData);

        // Commit the transaction
        DB::commit();

        // Return success response
        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $subCategory)) . ' data saved successfully!'
        ], 201); // 201 Created

    } catch (\Exception $e) {
        // Rollback transaction and delete uploaded images if something fails
        DB::rollBack();

        // Handle image deletions
        if (isset($imageNames)) {
            foreach ($imageNames as $field => $imageName) {
                if (!empty($imageName)) {
                    $imagePath = storage_path('app/public/assets/uploads/sub-category-images/' . $imageName);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }
        }

        // Return error response
        return response()->json([
            'error' => 'There was an error processing your request. Please try again. ' . $e->getMessage()
        ], 500); // 500 Internal Server Error
    }
}


    public function getCategories()
    {
        $category_list = Category::select(['id','category', 'image'])
            ->with(['heavy_equipment', 'vehicle_rent'])
            ->where('status',1)
            ->paginate(10)
            ->withQueryString();
        if($category_list){
            return NewCategoryResource::collection($category_list);
        }
        return response()->json([
            'msg'=> __('No category found'),
        ]);
    }

    
}
