<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->category,
            "image" => $this->getFullImageUrl($this->image), // Use the helper to get the full image URL
            "sub_categories" => $this->sub_categories->map(function ($sub_category) {
                return [
                    "id" => $sub_category->id,
                    "name" => $sub_category->sub_category,
                    "image" => $this->getFullImageUrl($sub_category->image),
                    "sub_sub_categories" => $sub_category->sub_sub_categories->map(function ($sub_sub_category) {
                        return [
                            "id" => $sub_sub_category->id,
                            "name" => $sub_sub_category->sub_sub_category,
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
            return null; // Return null if imageId is not provided
        }

        $imageDetails = get_attachment_image_by_id($imageId);

        // Return the image URL or null if not available
        return $imageDetails['img_url'] ?? null;
    }
}
