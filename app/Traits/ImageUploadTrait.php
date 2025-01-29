<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{
    public function handleEquipmentImages(Request $request, $subCategory)
    {
        // Folder path where all equipment images will be stored
        $uploadFolder = 'public/assets/uploads/sub-category-images/';

        // Define the image fields for equipment (can be adjusted per sub-category)
        $imageFields = $this->getImageFieldsForSubCategory($subCategory);

        $imageNames = [];

        foreach ($imageFields as $field) {
            if ($image = $request->file($field)) {
                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imageNames[$field] = $imageName;

                // Local storage logic
                $image->move(storage_path('app/' . $uploadFolder), $imageName);

            } else {
                $imageNames[$field] = ''; // If no image is provided, save an empty string
            }
        }

        return $imageNames;
    }

    // This method returns the image fields based on the sub-category
    protected function getImageFieldsForSubCategory($subCategory)
    {
        $imageFields = [
            'heavy_equipment' => [
                'data_certificate_image', 'driver_license_front_image',
                'driver_license_back_image', 'additional_equipment_images'
            ],
            'site_service_car' => [
                'data_certificate_image', 'driver_license_front_image',
                'driver_license_back_image', 'additional_equipment_images'
            ],
            // Add other sub-categories here and their respective image fields
        ];

        return $imageFields[$subCategory] ?? [];
    }
}

