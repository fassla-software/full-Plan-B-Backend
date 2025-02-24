<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\CraneRentRequest;
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
        // Define the fields that need to be decoded from JSON
        $jsonFields = [
            'additional_equipment_images',
            'load_data_documents',
            'insurance_documents',
            'operator_qualification_documents'
        ];

        // Decode JSON fields dynamically before validation
        $this->decodeJsonFieldsBeforeValidation($request, $jsonFields);

        // Validate data using the specific request class
        $requests = [
            MachineType::heavyEquipment->value => HeavyEquipmentRequest::class,
            MachineType::vehicleRental->value => VehicleRentRequest::class,
            MachineType::craneRental->value => CraneRentRequest::class,
        ];

        if (!isset($requests[$subCategory])) {
            return response()->json(['error' => 'Sub-category not found'], 404);
        }

        $request['equipment_type'] = $subSubCategory;
        $request['name'] = ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $subSubCategory));

        // Resolve and validate using the specific request class
        $validatedData = app($requests[$subCategory])->validated();
        $validatedData['user_id'] = auth('sanctum')->user()->id;

        // Handle images using the trait
        $validatedData = $this->processImages($validatedData, $subCategory);

        // Dynamically encode arrays before saving
        $validatedData = $this->encodeJsonFields($validatedData, $jsonFields);

        // Map sub-category to model
        $models = [
            MachineType::heavyEquipment->value => \App\Models\HeavyEquipment::class,
            MachineType::vehicleRental->value => \App\Models\VehicleRental::class,
            MachineType::craneRental->value => \App\Models\CraneRental::class,
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

    /**
     * Dynamically decode JSON fields if they are strings.
     */
/**
 * Decode JSON fields before validation by updating the request directly.
 */
    private function decodeJsonFieldsBeforeValidation(Request $request, array $jsonFields): void
    {
        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request[$field])) {
                $decodedValue = json_decode($request[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decodedValue]);
                }
            }
        }
    }


    /**
     * Dynamically encode array fields to JSON before saving.
     */
    private function encodeJsonFields(array $data, array $jsonFields): array
    {
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }
        return $data;
    }
    
}
