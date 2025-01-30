<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ImageUploadController extends Controller
{

    public function handleEquipmentImages(Request $request)
    {
        // Folder path where all equipment images will be stored
        $uploadFolder = 'public/assets/uploads/sub-category-images/';

        // Define the image fields for equipment (can be adjusted per sub-category)
        $imageFields = $this->getImageFieldsForSubCategory($subCategory);

        $imageNames = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if (is_array($request->file($field))) {
                    // Handle multiple images
                    $multipleImages = [];
                    foreach ($request->file($field) as $image) {
                        $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(storage_path('app/' . $uploadFolder), $imageName);
                        $multipleImages[] = $imageName;
                    }
                    $imageNames[$field] = json_encode($multipleImages); // Store as JSON
                } else {
                    // Handle single image
                    $image = $request->file($field);
                    $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(storage_path('app/' . $uploadFolder), $imageName);
                    $imageNames[$field] = $imageName;
                }
            } else {
                $imageNames[$field] = null; // Store null if no image is uploaded
            }
        }

        return $imageNames;
    }

    // This method returns the image fields based on the sub-category
    protected function getImageFieldsForSubCategory($subCategory)
    {
        $imageFields = [
            MachineType::heavyEquipment->value => [
                'data_certificate_image', 'driver_license_front_image',
                'driver_license_back_image', 'additional_equipment_images',
                'tractor_license_front_image', 'tractor_license_back_image',
                'flatbed_license_front_image', 'flatbed_license_back_image',
            ],
            MachineType::vehicleRental->value => [
                'data_certificate_image', 'driver_license_front_image',
                'driver_license_back_image', 'additional_vehicle_images', // Multiple images
                'vehicle_license_front_image', 'vehicle_license_back_image',
            ],
            // Add other sub-categories here and their respective image fields
        ];

        return $imageFields[$subCategory] ?? [];
    }
}