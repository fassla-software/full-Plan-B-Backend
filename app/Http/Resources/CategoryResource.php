<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = $request->header('Accept-Language', 'en');

        return [
            "id" => $this->id,
            "name" => $this->getTranslatedName($locale),
            "image" => $this->getFullImageUrl($this->image),
            "sub_categories" => $this->sub_categories->map(function ($sub_category) use ($locale) {
                return [
                    "id" => $sub_category->id,
                    "name" => $sub_category->getTranslatedName($locale),
                    "image" => $this->getFullImageUrl($sub_category->image),
                    "sub_sub_categories" => $sub_category->sub_sub_categories->map(function ($sub_sub_category) use ($locale) {
                        return [
                            "id" => $sub_sub_category->id,
                            "name" => $sub_sub_category->getTranslatedName($locale),
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
}

