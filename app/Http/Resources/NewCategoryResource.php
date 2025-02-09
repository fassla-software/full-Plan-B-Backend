<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->category,
            "image" => $this->image ? $this->getFullImageUrl($this->image) : null,
            "sub_categories" => $this->getSubCategories(),
        ];
    }

    private function getSubCategories()
    {
        $uploadFolder = 'assets/uploads/sub-category-images/';

        return $this->sub_categories()->map(function ($item) use ($uploadFolder) {
            return [
                "id" => $item->id,
                "name" => $item->name,
                "size" => $item->size ?? null,
                "image" => $item->image ? asset('storage/' . $uploadFolder . $item->image) : null,
                "sub_sub_categories" => $this->getSubSubCategories($item),
            ];
        });
    }


    private function getSubSubCategories($item)
    {
        // Check if the model has a relation for sub-sub-categories
        if (method_exists($item, 'subSubCategories')) {
            return $item->subSubCategories->map(function ($subSub) {
                return [
                    "id" => $subSub->id,
                    "name" => $subSub->name,
                    "image" => $subSub->image ? asset('storage/' . $subSub->image) : null,
                ];
            });
        }
        return null;
    }

    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null; // Return null if imageId is not provided
        }

        $imageDetails = get_attachment_image_by_id($imageId);

        // Return the image URL or null if not available
        return $imageDetails['img_url'] ?? null;
    }
}
