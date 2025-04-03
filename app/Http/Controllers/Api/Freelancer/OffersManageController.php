<?php

namespace App\Http\Controllers\Api\Freelancer;

use DateTime;
use App\Enums\MachineType;
use App\Enums\OperationType;
use Illuminate\Validation\Rule;
use App\Models\{NewProposal, User};
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use App\Services\OfferManagementService;
use Modules\Service\Entities\SubCategory;
use Illuminate\Http\{Request, JsonResponse};
use App\Http\Requests\StoreNewProposalRequest;
use App\Http\Requests\offers\UpdateOfferRequest;

class OffersManageController extends Controller
{
    protected $offerService;

    public function __construct(OfferManagementService $offerService)
    {
        $this->offerService = $offerService;
    }

    // add offer
    public function addOffer(StoreNewProposalRequest $request, $jobType, $jobId): JsonResponse
    {
        $validatedData = $request->validated();

        $modelClass = getModelClassFromType($jobType);

        if (!$modelClass) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid job type provided.',
            ], 400);
        }

        $requestEntry = \App\Models\Request::where('requestable_type', $modelClass)
            ->with('requestable')
            ->where('requestable_id', $jobId)
            ->first();

        if (!$requestEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No matching request found for the provided job type.',
            ], 400);
        }

        $validatedData['request_id'] = $requestEntry->id;

        $user = auth('sanctum')->user();
        $validatedData['user_id'] = $user->id;

        $requestValidator = Validator::make($validatedData, [
            'request_id' => [
                Rule::unique('new_proposals')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
        ], [
            'request_id.unique' => 'This offer has already been submitted by this user.',
        ]);

        if ($requestValidator->fails()) {
            return response()->json($requestValidator->errors(), 422);
        }

        $currentSubscripiton = getCurrentUserSubsicription($user);
        if ($currentSubscripiton) {
            minusUserAvailableLimit($currentSubscripiton, OperationType::makeOffer);
        }

        $proposal = NewProposal::create($validatedData);

        $recipientUser = User::find($requestEntry->user_id);

        // send notification via firebase
        $request = $this->offerService->pushNotification($recipientUser, $proposal);

        return response()->json([
            'message' => 'Proposal created successfully.',
            'category_slug' => $jobType,
            'offer' => $proposal
        ]);
    }

    // get all your requests and number of offers on it
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
        $sub_category = SubCategory::findOrFail($sub_category_id);

        $perPage = $request->input('per_page', 15);

        $records = $jobModel::query()
            ->with(['request.newProposals', 'subCategory', 'user:id,first_name,last_name,image'])
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
                'offers_is_stoped' => $record->isStopped,
                'offers_count' => $record->request ? $record->request->newProposals->count() : 0,
                'image' => getFullImageUrl($record->subCategory->image),
                'user' => $record->user,
            ];
        })->sortByDesc('offers_count')->values();

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_slug' => $sub_category->getTranslatedName($locale),
            'sub_category_id' => $sub_category_id,
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

    // get offers on your request
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

        $offers = NewProposal::query()
            ->with([
                'user:id,first_name,last_name,image',
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
            'image' => getFullImageUrl($sub_category->image),
            'sub_category_id' => $sub_category_id,
            'category_slug' => $jobType,
            'offers' => $offers
        ]);
    }

    // get offer details
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

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $eqName = $sub_category->getTranslatedName($request->header('Accept-Language', 'en'));

        $eqImage = getFullImageUrl($sub_category->image);

        $offer = NewProposal::query()
            ->with([
                'user:id,first_name,last_name,image',
                'request:id,requestable_id,requestable_type',
                'request.requestable:id,size,work_site_location,hour,day,month'
            ])
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

    // get details of offer owner
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

        $userData = $offer->user->only([
            'id',
            'first_name',
            'last_name',
            'experience_level',
            'email',
            'phone',
            'image',
        ]);

        return response()->json([
            'user' => $userData,
        ], 200);
    }

    // updated needed
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

        $proposal = NewProposal::with(['request.requestable.subCategory', 'user'])
            ->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $user_id = $proposal->user_id;

        $equipmentModel = getEquipmentModelFromType($jobType);

        $equipment = $equipmentModel::where('user_id', $user_id)
            ->where('sub_category_id', $proposal->request->requestable->subCategory->id)->first();

        if (!$equipment) return response()->json(['message' => 'there is no equipment']);

        $additionalImages = collect($equipment->additional_equipment_images ?? [])
            ->map(fn($image) => asset('storage/assets/uploads/equipments/' . $image))
            ->all();

        return response()->json([
            'message' => 'success',
            'data' => [
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
            ]
        ]);
    }

    // update nedeed
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

        $proposal = NewProposal::with(['request.requestable.subCategory', 'user'])
            ->findOrFail($offer_id);

        if (!$proposal->request || $proposal->request->requestable_type !== $categoryModel) {
            return response()->json(['message' => 'No HeavyEquipmentJob found for this proposal'], 404);
        }

        $job = $proposal->request->requestable;

        $user_id = $proposal->user_id;

        $equipmentModel = getEquipmentModelFromType($jobType);

        $equipment = $equipmentModel::where('user_id', $user_id)
            ->where('sub_category_id', $proposal->request->requestable->subCategory->id)->first();

        if (!$equipment) return response()->json(['message' => 'there is no equipment']);

        return response()->json([
            'message' => 'success',
            'data' => [
                'equipment' => $equipment,
                'name' => $job->subCategory?->getTranslatedName($locale),
                'other_terms' => $proposal->other_terms,
            ]
        ]);
    }

    // stop recieving offer on request
    public function stopReceivingOffers(Request $request, string $jobType, string $request_id): JsonResponse
    {
        $validatedData = Validator::make(
            compact('jobType'),
            [
                'jobType'  => ['required', new Enum(MachineType::class)],
            ]
        );

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validatedData->errors()
            ], 422);
        }

        $job = getModelClassFromType($jobType)::findOrFail($request_id);

        $job->update(['isStopped' => true]);

        return response()->json(['message' => 'Offers stopped successfully']);
    }

    // update offer
    public function updateOffer(UpdateOfferRequest $request, NewProposal $newProposal): JsonResponse
    {
        if (!$newProposal) {
            return response()->json(
                ['error' => 'Offer not found'],
                404
            );
        }
        $newProposal->update($request->validated());

        $user = auth('sanctum')->user();
        $currentSubscripiton = getCurrentUserSubsicription($user);
        if ($currentSubscripiton) {
            minusUserAvailableLimit($currentSubscripiton, OperationType::updateOffer);
        }

        return response()->json(
            [
                'message' => 'Offer updated successfully',
                'offer' => $newProposal
            ]
        );
    }

    // delete offer
    public function deleteOffer(NewProposal $newProposal): JsonResponse
    {
        if (!$newProposal) {
            return response()->json(
                ['error' => 'Offer not found'],
                404
            );
        }

        $newProposal->delete();

        $user = auth('sanctum')->user();
        $currentSubscripiton = getCurrentUserSubsicription($user);
        if ($currentSubscripiton) {
            minusUserAvailableLimit($currentSubscripiton, OperationType::deleteOffer);
        }

        return response()->json(
            ['message' => 'Offer deleted successfully']
        );
    }

    // get offer rank
    public function getMyOfferRank(string $jobType, int $job_id, NewProposal $newProposal): JsonResponse
    {
        if (!$newProposal) {
            return response()->json(
                ['error' => 'Offer not found'],
                404
            );
        }

        $validator = Validator::make([
            'jobType' => $jobType,
        ], [
            'jobType' => ['required', new Enum(MachineType::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $job = getModelClassFromType($jobType)::findOrFail($job_id);
        $offersOfJob = $job?->request?->newProposals;

        if (!$offersOfJob || $offersOfJob->isEmpty()) {
            return response()->json(['message' => 'No offers found for this job'], 404);
        }

        $sortedOffers = $offersOfJob->sortBy('price')->values();

        $rank = $sortedOffers->search(fn($offer) => $offer->id === $newProposal->id) + 1;

        return response()->json([
            'rank' => $rank,
            'total_offers' => $sortedOffers->count(),
        ]);
    }

    private function getRemainingTimeForOfferAvailability($end_at)
    {
        $endDateTime = new DateTime($end_at);
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
