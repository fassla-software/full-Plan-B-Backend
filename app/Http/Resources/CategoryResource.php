<?php

namespace App\Http\Resources;

use App\Enums\MachineType;
use App\Models\VehicleRental;
use App\Models\CraneRentalJob;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentJob;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = $request->header('Accept-Language', 'en');
        $userId = auth('sanctum')?->user()?->id;

        $subCategories = $this->sub_categories ? $this->sub_categories->map(function ($sub_category) use ($locale, $userId) {
            return [
                "id" => $sub_category->id,
                "name" => $sub_category->getTranslatedName($locale),
                "label" => $this->getEnumLabel($sub_category->getTranslatedName('en')),
                "image" => $this->getFullImageUrl($sub_category->image),
                "equipments_count" => $this->getSubCategoryCountOfUser($sub_category->id, $userId, $this->id),
                "requests_count" => $this->getRequestsOfEquipment($sub_category->id, $userId, $this->id),
                "sub_sub_categories" => $sub_category->sub_sub_categories->map(function ($sub_sub_category) use ($locale) {
                    return [
                        "id" => $sub_sub_category->id,
                        "name" => $sub_sub_category->getTranslatedName($locale),
                        "label" => $this->getEnumLabel($sub_sub_category->getTranslatedName('en')),
                        "image" => $this->getFullImageUrl($sub_sub_category->image),
                    ];
                }),
            ];
        }) : [];

        $totalUserCount = collect($subCategories)->sum('equipments_count');
        $totalRequestsCount = collect($subCategories)->sum('requests_count');

        return [
            "id" => $this->id,
            "name" => $this->getTranslatedName($locale),
            "label" => $this->getEnumLabel($this->getTranslatedName('en')),
            "image" => $this->getFullImageUrl($this->image),
            "total_equipments_count" => $totalUserCount,
            "total_requests_count" => $totalRequestsCount,
            "sub_categories" => $subCategories,
        ];
    }


    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null;
        }
        $imageDetails = get_attachment_image_by_id($imageId);
        return $imageDetails['img_url'] ?? null;
    }

    /**
     * Dynamically fetch the label from the enum based on category name.
     */
    private function getEnumLabel($name)
    {
        $enumCases = MachineType::cases();

        foreach ($enumCases as $case) {
            // Normalize both strings for accurate comparison
            $normalizedEnumValue = strtolower(str_replace(' ', '', $case->value));
            $normalizedName = strtolower(str_replace(' ', '', $name));

            if ($normalizedEnumValue === $normalizedName) {
                return __($case->name); // Return the translated enum label
            }
        }

        return $name; // Fallback if no match found
    }

    // get requests count of subcategory count of user
    private function getSubCategoryCountOfUser($sub_category, $userId, $categoryId)
    {
        if (!isset($userId)) return 0;

        $count = 0;
        foreach ([HeavyEquipment::class, VehicleRental::class] as $model) {
            $count += $model::query()->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->where('sub_category_id', $sub_category)->count();
        }

        return $count;
    }

    // get requests count of (user => every sub category (equepments))
    private function getRequestsOfEquipment($sub_category, $userId, $categoryId)
    {
        if (!isset($userId)) return 0;

        $count = 0;
        foreach ([HeavyEquipmentJob::class, CraneRentalJob::class] as $model) {
            $count += $model::query()->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->where('sub_category_id', $sub_category)->count();
        }

        return $count;
    }

    private function getOffersOffRequestsCount($sub_category, $userId) {}
}
