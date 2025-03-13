<?php

namespace App\Http\Controllers\Api\Freelancer;

use DateTime;
use App\Enums\MachineType;
use App\Models\NewProposal;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use Modules\Service\Entities\SubCategory;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Requests\offers\UpdateOfferRequest;

class OffersManageController extends Controller
{
    public function getGroupOfOffers(Request $request, string $jobType, int $sub_category_id): JsonResponse
    {
        $validator = Validator::make([
            'jobType' => $jobType,
            'sub_category_id' => $sub_category_id,
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jobModel = getModelClassFromType($jobType);
        $locale = $request->header('Accept-Language', 'en');

        $perPage = $request->input('per_page', 15);

        $records = $jobModel::query()
            ->with(['request.newProposals', 'subCategory', 'user:id,first_name,last_name'])
            ->where('user_id', $user->id)
            ->where('sub_category_id', $sub_category_id)
            ->paginate($perPage);

        $formattedRecords = $records->map(function ($record) use ($jobType, $locale) {
            return [
                'id' => $record->id,
                'name' => $record->subCategory->getTranslatedName($locale),
                'size' => $record->size,
                'work_site_location' => $record->work_site_location,
                'hour' => $record->hour,
                'weak' => $record->weak,
                'month' => $record->month,
                'CategorySlug' => $jobType,
                'offers_count' => $record->request ? $record->request->newProposals->count() : 0,
                'image' => $this->getFullImageUrl($record->subCategory->image),
                'user' => $record?->user,
            ];
        });

        return response()->json([
            'data' => $formattedRecords,
            'pagination' => [
                'total' => $records->total(),
                'per_page' => $records->perPage(),
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
                'next_page_url' => $records->nextPageUrl(),
                'prev_page_url' => $records->previousPageUrl(),
            ]
        ]);
    }

    public function getOffers(Request $request, string $jobType, int $sub_category_id, int $job_id): JsonResponse
    {
        $validator = Validator::make([
            'jobType' => $jobType,
            'sub_category_id' => $sub_category_id,
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $categoryModel = getModelClassFromType($jobType);

        $sub_category = SubCategory::findOrFail($sub_category_id);
        $eqName = $sub_category->getTranslatedName($request->header('Accept-Language', 'en'));
        $eqImage = $sub_category->image ? asset('storage/assets/uploads/sub-category/' . $sub_category->image) : null;

        $offers = NewProposal::query()
            ->with([
                'user:id,first_name,last_name',
                'request:id,requestable_id,requestable_type',
                'request.requestable:id,size,work_site_location,hour,day,month'
            ])->whereHas('request', function ($query) use ($categoryModel, $job_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($job_id) {
                        $query->where('id', $job_id);
                    });
            })
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'name' => $eqName,
            'image' => $eqImage,
            'sub_category_id' => $sub_category_id,
            'category_slug' => $jobType,
            'offers' => $offers
        ]);
    }

    public function getOfferDetails(Request $request, string $jobType, int $sub_category_id, string $offer_id): JsonResponse
    {
        $validator = Validator::make([
            'jobType' => $jobType,
            'sub_category_id' => $sub_category_id,
            'offer_id' => $offer_id
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'offer_id' => 'required|string|exists:new_proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $categoryModel = getModelClassFromType($jobType);

        $sub_category = SubCategory::findOrFail($sub_category_id);
        $eqName = $sub_category->getTranslatedName($request->header('Accept-Language', 'en'));
        $eqImage = $sub_category->image ? asset('storage/assets/uploads/sub-category/' . $sub_category->image) : null;

        $offer = NewProposal::query()
            ->with([
                'user:id,first_name,last_name',
                'request:id,requestable_id,requestable_type',
                'request.requestable:id,size,work_site_location,hour,day,month'
            ])->whereHas('request', function ($query) use ($categoryModel, $user, $sub_category_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($user, $sub_category_id) {
                        $query->where('user_id', $user->id)
                            ->where('sub_category_id', $sub_category_id);
                    });
            })
            ->where('id', $offer_id)
            ->first();

        if ($offer && ($offer->isSeen == 0)) {
            $offer->update([
                'isSeen' => 1
            ]);
        }

        if ($offer) $offer['remaining_time'] = $this->getRemainingTimeForOfferAvailability($offer->offer_ends_at);

        return response()->json([
            'name' => $eqName,
            'image' => $eqImage,
            'sub_category_id' => $sub_category_id,
            'category_slug' => $jobType,
            'offer' => $offer
        ]);
    }

    public function getContactOffOfferOwner(string $offer_id): JsonResponse
    {
        $validator = Validator::make([
            'offer_id' => $offer_id
        ], [
            'offer_id' => 'nullable|string|exists:new_proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $offer = NewProposal::with(['user:id,first_name,last_name,experience_level,email,phone,image'])->findOrFail($offer_id);

        $imageUrl = $offer->user->image ? asset('storage/assets/uploads/profile/' . $offer->user->image) : null;

        $userData = $offer->user->only([
            'id',
            'first_name',
            'last_name',
            'experience_level',
            'email',
            'phone'
        ]);
        $userData['image'] = $imageUrl;

        return response()->json([
            'user' => $userData,
        ], 200);
    }

    public function getEquipmentImages(Request $request, string $jobType, string $offer_id): JsonResponse
    {
        $validator = Validator::make([
            'jobType' => $jobType,
            'offer_id' => $offer_id
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
            'offer_id' => 'nullable|string|exists:new_proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $locale = $request->header('Accept-Language', 'en');

        $categoryModel = getModelClassFromType($jobType);

        $proposal = NewProposal::with('request.requestable.heavy_equipment.subCategory')
            ->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $equipment = $proposal->request->requestable->heavy_equipment;

        if (!$equipment) {
            return response()->json(['message' => 'No equipment found for this proposal'], 404);
        }

        $additionalImages = collect($equipment->additional_equipment_images ?? [])
            ->map(fn($image) => asset('storage/assets/uploads/equipments/' . $image))
            ->all();

        return response()->json([
            'name' => $equipment->subCategory?->getTranslatedName($locale),
            'data_certificate_image' => $equipment->data_certificate_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->data_certificate_image)
                : null,
            'driver_license_front_image' => $equipment->driver_license_front_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->driver_license_front_image)
                : null,
            'driver_license_back_image' => $equipment->driver_license_back_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->driver_license_back_image)
                : null,
            'tractor_license_front_image' => $equipment->tractor_license_front_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->tractor_license_front_image)
                : null,
            'tractor_license_back_image' => $equipment->tractor_license_back_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->tractor_license_back_image)
                : null,
            'flatbed_license_front_image' => $equipment->flatbed_license_front_image
                ? asset('storage/assets/uploads/equipments/' . $equipment->flatbed_license_front_image)
                : null,
            'additional_equipment_images' => $additionalImages,
        ]);
    }

    public function getEquipmentDaitls(Request $request, string $jobType, string $offer_id): JsonResponse
    {
        $validator = Validator::make([
            'jobType' => $jobType,
            'offer_id' => $offer_id
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
            'offer_id' => 'nullable|string|exists:new_proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $locale = $request->header('Accept-Language', 'en');

        $categoryModel = getModelClassFromType($jobType);

        $proposal = NewProposal::with('request.requestable.heavy_equipment.subCategory')
            ->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $job = $proposal->request->requestable;

        if (!$job || !$job->heavy_equipment) {
            return response()->json(['message' => 'No equipment details found for this proposal'], 404);
        }

        return response()->json([
            'name' => $job->subCategory?->getTranslatedName($locale),
            'size' => $job->size,
            'model' => $job->heavy_equipment->model,
            'year_of_manufacture' => $job->heavy_equipment->year_of_manufacture,
            'moves_on' => $job->heavy_equipment->moves_on,
            'current_equipment_location' => $job->heavy_equipment->current_equipment_location,
            'other_terms' => $proposal->other_terms,
        ]);
    }

    public function stopReceivingOffers(Request $request, string $jobType, string $offer_id): JsonResponse
    {
        $validatedData = Validator::make(
            compact('jobType', 'offer_id'),
            [
                'jobType'  => ['required', new Enum(MachineType::class)],
                'offer_id' => 'nullable|string|exists:new_proposals,id',
            ]
        );

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validatedData->errors()
            ], 422);
        }

        $categoryModel = getModelClassFromType($jobType);

        $proposal = NewProposal::with('request.requestable.heavy_equipment.subCategory')
            ->find($offer_id);

        if (!$proposal || !$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $job = $proposal->request->requestable;

        if (!$job) {
            return response()->json(['message' => 'No equipment details found for this proposal'], 404);
        }

        $job->update(['isStopped' => true]);

        return response()->json(['message' => 'Offers stopped successfully']);
    }

    public function updateOffer(UpdateOfferRequest $request, NewProposal $newProposal): JsonResponse
    {
        $newProposal->update($request->validated());
        return response()->json(
            [
                'message' => 'Offer updated successfully',
                'offer' => $newProposal
            ]
        );
    }

    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null;
        }
        $imageDetails = get_attachment_image_by_id($imageId);
        return $imageDetails['img_url'] ?? null;
    }

    private function getRemainingTimeForOfferAvailability($end_at)
    {
        $endDateTime = new DateTime($end_at . ' 23:59:59');
        $currentDateTime = new DateTime();

        if ($currentDateTime > $endDateTime) {
            return "00:00:00";
        }

        $interval = $currentDateTime->diff($endDateTime);

        $remainingHours = $interval->days * 24 + $interval->h;
        $remainingMinutes = $interval->i;
        $remainingSeconds = $interval->s;

        return sprintf('%02d:%02d:%02d', $remainingHours, $remainingMinutes, $remainingSeconds);
    }
}
