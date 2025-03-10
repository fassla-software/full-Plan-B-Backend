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

    public function getContactOffOfferOwner(Request $request, $offer_id)
    {
        $offer = NewProposal::with(['user:id,first_name,last_name,experience_level,email,phone,image'])->findOrFail($offer_id);

        $imageName = $offer->user->image ? asset('storage/assets/uploads/profile/' . $offer->user->image) : null;

        $userData = $offer->user->toArray();
        $userData['image'] = $imageName;

        return response()->json([
            'user' => $userData,
        ]);
    }

    public function getEquipmentImages(Request $request, $jobType, $offer_id)
    {
        $locale = $request->header('Accept-Language', 'en');
        $categoryModel = $this->getModelClassFromType($jobType);

        $proposal = NewProposal::with('request.requestable.heavy_equipment')->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $equipment = $proposal?->request?->requestable_type?->heavy_equipment;

        // return $equipment;

        return response()->json([
            'name' => $equipment?->subCategory?->getgetTranslatedName($locale),
            'data_certificate_image' => $equipment?->data_certificate_image ? asset('storage/assets/uploads/equipmets/' . $equipment->data_certificate_image) : null,
            'driver_license_front_image' => $equipment?->driver_license_front_image ? asset('storage/assets/uploads/equipmets/' . $equipment->driver_license_front_image) : null,
            'driver_license_back_image' => $equipment?->driver_license_back_image ? asset('storage/assets/uploads/equipmets/' . $equipment->driver_license_back_image) : null,
            'tractor_license_front_image' => $equipment?->tractor_license_front_image ? asset('storage/assets/uploads/equipmets/' . $equipment->tractor_license_front_image) : null,
            'tractor_license_back_image' => $equipment?->tractor_license_back_image ? asset('storage/assets/uploads/equipmets/' . $equipment->tractor_license_back_image) : null,
            'flatbed_license_front_image' => $equipment?->flatbed_license_front_image ? asset('storage/assets/uploads/equipmets/' . $equipment->flatbed_license_front_image) : null,
            'additional_equipment_images' => $equipment?->additional_equipment_images
                ? collect($equipment->additional_equipment_images)->map(fn($image) => asset('storage/assets/uploads/equipmets/' . $image))->all()
                : [],
        ]);
    }

    public function getEquipmentDaitls(Request $request, $jobType, $offer_id)
    {
        $locale = $request->header('Accept-Language', 'en');
        $categoryModel = $this->getModelClassFromType($jobType);

        $proposal = NewProposal::with('request.requestable.heavy_equipment')->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $job = $proposal?->request?->requestable;

        return response()->json(
            [
                'name' => $job?->subCategory?->getTranslatedName($locale),
                'size' => $job?->size,
                'model' => $job?->heavy_equipment?->model,
                'year_of_manufacture' => $job?->heavy_equipment?->year_of_manufacture,
                'moves_on' => $job?->heavy_equipment?->moves_on,
                'current_equipment_location' => $job?->heavy_equipment?->current_equipment_location,
                'other_terms' => $proposal->other_terms,
            ]
        );
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
