<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\HeavyEquipmentJobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NewJobController extends Controller
{
    public function storeData(Request $request, $subCategory, $subSubCategory)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            // Validate data using the specific request class
            $requests = [
                MachineType::heavyEquipment->value => HeavyEquipmentJobRequest::class,
                // Add other sub-category request classes here
            ];

            if (!isset($requests[$subCategory])) {
                return response()->json(['error' => 'Sub-category not found'], 404);
            }
            $request['equipment_type'] = $subSubCategory;
            // Resolve and validate using the specific request class
            $validatedData = app($requests[$subCategory])->validated();
            $validatedData['user_id'] = 1;
            // Map sub-category to model
            $models = [
                MachineType::heavyEquipment->value => \App\Models\HeavyEquipmentJob::class,
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
            // Return error response
            return response()->json([
                'error' => 'There was an error processing your request. Please try again. ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}
