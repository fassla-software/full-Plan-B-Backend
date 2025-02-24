<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\MachineType;

class CategoryResource extends JsonResource
{
public function toArray($request)
{
    $locale = $request->header('Accept-Language', 'en');

    return [
        "id" => $this->id,
        "name" => $this->getTranslatedName($locale),
        "label" => $this->getEnumLabel($this->getTranslatedName('en')), // Pass the English name
        "image" => $this->getFullImageUrl($this->image),
        "sub_categories" => $this->sub_categories->map(function ($sub_category) use ($locale) {
            return [
                "id" => $sub_category->id,
                "name" => $sub_category->getTranslatedName($locale),
                "label" => $this->getEnumLabel($sub_category->getTranslatedName('en')),
                "image" => $this->getFullImageUrl($sub_category->image),
                "sub_sub_categories" => $sub_category->sub_sub_categories->map(function ($sub_sub_category) use ($locale) {
                    return [
                        "id" => $sub_sub_category->id,
                        "name" => $sub_sub_category->getTranslatedName($locale),
                        "label" => $this->getEnumLabel($sub_sub_category->getTranslatedName('en')),
                        "image" => $this->getFullImageUrl($sub_sub_category->image),
                    ];
                }),
            ];
        }),
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
}
