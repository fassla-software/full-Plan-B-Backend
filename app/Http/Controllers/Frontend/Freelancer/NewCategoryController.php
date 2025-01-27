<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\HeavyEquipmentRequest;
use App\Http\Requests\CategoryRequest\SiteServiceCarRequest;
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
        // Start transaction
        DB::beginTransaction();

        try {
            // Validate data using the specific request class
            $requests = [
                'heavy_equipment' => HeavyEquipmentRequest::class,
                'site_service_car' => SiteServiceCarRequest::class,
                // Add other sub-category request classes here
            ];

            if (!isset($requests[$subCategory])) {
                abort(404, 'Sub-category not found');
            }

            // Resolve and validate using the specific request class
            $validatedData = app($requests[$subCategory])->validated();

            // Handle file uploads for equipment images
            $imageNames = $this->handleEquipmentImages($request, $subCategory);

            // Merge the image names with the validated data
            $validatedData = array_merge($validatedData, $imageNames);

            // Map sub-category to model
            $models = [
                'heavy_equipment' => \App\Models\HeavyEquipment::class,
                'site_service_car' => \App\Models\SiteServiceCar::class,
                // Add other sub-category models here
            ];
            $model = $models[$subCategory];
            $model::create($validatedData);

            // Commit the transaction
            DB::commit();

            toastr_success(ucfirst(str_replace('_', ' ', $subCategory)) . ' data saved successfully!');
            return back();

        } catch (\Exception $e) {
            // Rollback transaction and delete uploaded images if something fails
            DB::rollBack();

            // Delete images if uploaded
            foreach ($imageNames as $field => $imageName) {
                if (!empty($imageName)) {
                    // Adjust image path as per your storage method
                    $imagePath = storage_path('app/public/assets/uploads/sub-category-images/' . $imageName);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }

            // Return a message or redirect to a previous page with error
            return back()->withError('There was an error processing your request. Please try again.');
        }
    }
    
    
}
