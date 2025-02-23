<?php

namespace App\Traits;

use App\Enums\MachineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{
    /**
     * Handles single and multiple image uploads dynamically.
     */
    public function processImages(array $validatedData, $subCategory)
    {
        // Get image fields dynamically based on the sub-category
        $imageFields = $this->getImageFieldsForSubCategory($subCategory);

        // Process each image field
        foreach ($imageFields as $field) {
            if (!empty($validatedData[$field])) {
                if (is_array($validatedData[$field])) {
                    // Multiple images (array)
                    $validatedData[$field] = array_map(function ($url) {
                        return basename($url);
                    }, $validatedData[$field]);

                    // Convert to JSON for storage
                    $validatedData[$field] = json_encode($validatedData[$field]);
                } else {
                    // Single image
                    $validatedData[$field] = basename($validatedData[$field]);
                }
            }
        }

        return $validatedData;
    }

    /**
     * Dynamically fetch image fields based on sub-category.
     */
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
                'driver_license_back_image', 'additional_vehicle_images',
                'vehicle_license_front_image', 'vehicle_license_back_image',
            ],

            MachineType::craneRental->value => [
                'additional_equipment_images', 'vehicle_license_front',
                'vehicle_license_back', 'driver_license_front',
                'driver_license_back', 'load_data_documents',
                'insurance_documents', 'operator_qualification_documents',
            ],

            MachineType::craneRental->value => [
                'load_image',
            ],
        ];

        return $imageFields[$subCategory] ?? [];
    }
}


