<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Enums\MachineType;
use App\Models\NewProposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = $request->header('Accept-Language', 'en');
        $userId = auth('sanctum')?->user()?->id;

        $translatedName = $this->getTranslatedName($locale);
        $slugLabel = $this->getEnumLabel($this->getTranslatedName('en'));

        $subCategories = $this->sub_categories ? $this->sub_categories->map(function ($sub_category) use ($locale, $userId, $slugLabel) {

            return [
                "id" => $sub_category->id,
                "name" => $sub_category->getTranslatedName($locale),
                "label" => $this->getEnumLabel($sub_category->getTranslatedName('en')),
                "image" => $this->getFullImageUrl($sub_category->image),
                "equipments_count" => $this->getSubCategoryCountOfUser($sub_category->id, $userId, $slugLabel),
                "requests_count" => $this->getRequestsOfEquipment($sub_category->id, $userId, $slugLabel),
                "offers_count" => $this->getOffersOffRequestsCount($sub_category->id, $userId, $slugLabel),
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
            "name" => $translatedName,
            "label" => $slugLabel,
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
    private function getSubCategoryCountOfUser($sub_category, $userId, $slug)
    {
        if (!isset($userId)) return 0;

        $categoryModel = getEquipmentModelFromType($slug);

        return isset($categoryModel) ? $categoryModel::query()->where('user_id', $userId)
            ->where('sub_category_id', $sub_category)->count() : 0;
    }

    // get requests count of (user => every sub category (equepments))
    private function getRequestsOfEquipment($sub_category, $userId, $slug)
    {
        if (!isset($userId)) return 0;

        $categoryModel = getModelClassFromType($slug);

        $userEquipments = getEquipmentModelFromType($slug)::select(['id', 'lat', 'long'])
            ->where('user_id', $userId)
            ->where('sub_category_id', $sub_category)
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get();

        $distanceConditions = $userEquipments->map(function ($equipment) {
            return DB::raw('
            (6371 * acos(cos(radians(' . $equipment->lat . ')) * cos(radians(lat)) * 
            cos(radians(`long`) - radians(' . $equipment->long . ')) + sin(radians(' . $equipment->lat . ')) * 
            sin(radians(lat)))) <= search_radius
        ');
        })->toArray();

        $records = $categoryModel::where('sub_category_id', $sub_category)
            ->where('user_id', '<>', $userId)
            ->whereDate('max_offer_deadline', '>=', Carbon::today())
            ->where(function ($query) use ($distanceConditions) {
                foreach ($distanceConditions as $condition) {
                    $query->orWhereRaw($condition);
                }
            })
            ->count();

        return $records;
    }

    private function getOffersOffRequestsCount($sub_category_id, $userId, $slug): int
    {
        $categoryModel = getModelClassFromType($slug);

        return NewProposal::query()
            ->whereHas('request', function ($query) use ($categoryModel, $userId, $sub_category_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($userId, $sub_category_id) {
                        $query->where('user_id', $userId)
                            ->where('sub_category_id', $sub_category_id);
                    });
            })->count();
    }
}
