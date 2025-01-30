<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\HeavyEquipmentRequest;
use App\Http\Requests\CategoryRequest\VehicleRentRequest;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NewCategoryController extends Controller
{
    use ImageUploadTrait;

//    public function showForm($subCategory)
//    {
//        $views = [
//            'heavy_equipment' => 'forms.heavy_equipment',
//            'site_service_car' => 'forms.site_service_car',
//            // Add other sub-categories here
//        ];
//
//        if (!isset($views[$subCategory])) {
//            abort(404, 'Sub-category not found');
//        }
//
//        return view($views[$subCategory]);
//    }

    public function storeData(Request $request, $subCategory, $subSubCategory)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validate data using the specific request class
            $requests = [
                MachineType::heavyEquipment->value => HeavyEquipmentRequest::class,
                MachineType::vehicleRental->value => VehicleRentRequest::class,
                // Add other sub-category request classes here
            ];

            if (!isset($requests[$subCategory])) {
                return response()->json(['error' => 'Sub-category not found'], 404);
            }
            $request['equipment_type'] = $subSubCategory;
            // Resolve and validate using the specific request class
            $validatedData = app($requests[$subCategory])->validated();

            // Handle file uploads for equipment images
//            $imageNames = $this->handleEquipmentImages($request, $subCategory);
//
//            // Merge the image names with the validated data
//            $validatedData = array_merge($validatedData, $imageNames);
            // Map sub-category to model
            $models = [
                MachineType::heavyEquipment->value => \App\Models\HeavyEquipment::class,
                MachineType::vehicleRental->value => \App\Models\VehicleRent::class,
                // Add other sub-category models here
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

            // Delete images if uploaded
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
                'error' => 'There was an error processing your request. Please try again.'
            ], 500); // 500 Internal Server Error
        }
    }

//    public function getFormForApi($subCategory)
//{
//    // Map sub-categories to their respective request classes
//    $requests = [
//        'heavy_equipment' => HeavyEquipmentRequest::class,
//        'site_service_car' => SiteServiceCarRequest::class,
//        // Add other sub-category request classes here
//    ];
//
//    // Check if the sub-category exists
//    if (!isset($requests[$subCategory])) {
//        return response()->json(['error' => 'Sub-category not found'], 404);
//    }
//
//    // Get the request class for the sub-category
//    $requestClass = $requests[$subCategory];
//
//    // Instantiate the request class to access its rules
//    $requestInstance = new $requestClass();
//
//    // Get the validation rules from the request class
//    $validationRules = $requestInstance->rules();
//
//    // Extract form fields and their types based on validation rules
//    $formFields = [];
//    foreach ($validationRules as $field => $rule) {
//        // Define the default form field type (you can improve this mapping as needed)
//        $fieldType = 'text'; // Default type
//        if (strpos($rule, 'file') !== false) {
//            $fieldType = 'file';
//        } elseif (strpos($rule, 'integer') !== false || strpos($rule, 'numeric') !== false) {
//            $fieldType = 'number';
//        } elseif (strpos($rule, 'string') !== false) {
//            $fieldType = 'text';
//        } elseif (strpos($rule, 'boolean') !== false) {
//            $fieldType = 'select'; // Yes/No options
//        } elseif (strpos($rule, 'nullable') !== false && strpos($rule, 'string') !== false) {
//            $fieldType = 'textarea';
//        }
//
//        // Add the field and its type to the form fields array
//        $formFields[] = [
//            'name' => $field,
//            'type' => $fieldType,
//            'label' => ucwords(str_replace('_', ' ', $field)), // Field label based on the field name
//        ];
//    }
//
//    // Return the form structure with the fields and types
//    return response()->json([
//        'subCategory' => ucfirst(str_replace('_', ' ', $subCategory)),
//        'formFields' => $formFields,
//    ]);
//}

    
}
