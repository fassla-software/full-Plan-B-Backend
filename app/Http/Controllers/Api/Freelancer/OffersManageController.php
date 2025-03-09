<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Models\NewProposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\SubCategory;

class OffersManageController extends Controller
{
    //
    public function getOffers(Request $request, $jobType, $sub_category_id, ?string $offer_id = null)
    {
        $locale = $request->header('Accept-Language', 'en');
        $categoryModel = $this->getModelClassFromType($jobType);
        $userId = auth('sanctum')?->user()?->id;

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $offers = NewProposal::query()->with('user:id,first_name,last_name')
            ->whereHas('request', function ($query) use ($categoryModel, $userId, $sub_category_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($userId, $sub_category_id) {
                        $query->where('user_id', $userId)
                            ->where('sub_category_id', $sub_category_id);
                    });
            })
            ->when($offer_id, fn($q) => $q->where('id', $offer_id))
            ->get();

        return response()->json([
            'name' => $sub_category->getTranslatedName($locale),
            'sub_category_id' => $sub_category_id,
            'category_slug' => $jobType,
            'offers' => $offers
        ]);
    }

    private function getModelClassFromType($type)
    {
        $types = [
            MachineType::heavyEquipment->value => \App\Models\HeavyEquipmentJob::class,
            MachineType::vehicleRental->value => \App\Models\VehicleRentalJob::class,
            MachineType::craneRental->value => \App\Models\CraneRentalJob::class,
            // Add other sub-category models here
        ];

        return $types[$type] ?? null;
    }
}
