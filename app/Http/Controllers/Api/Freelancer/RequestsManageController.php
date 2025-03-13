<?php

namespace App\Http\Controllers\Api\Freelancer;

use DateTime;
use App\Enums\MachineType;
use App\Models\NewProposal;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use Modules\Service\Entities\SubCategory;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Requests\requests\UpdateRequestRequest;

class RequestsManageController extends Controller
{
    public function getAllRequestsOfEquipment(Request $request, $jobType, $sub_category_id): JsonResponse
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


        $locale = $request->header('Accept-Language', 'en');
        $sub_category = SubCategory::findOrFail($sub_category_id);
        $eqName = $sub_category->getTranslatedName($locale);

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $equipmentModel = getEquipmentModelFromType($jobType);

        // $eqImage = $sub_category->image ? asset('storage/assets/uploads/sub-category/' . $sub_category->image) : null;
        $eqImage = $this->getFullImageUrl($sub_category->image);


        $records = $equipmentModel::query()
            ->whereHas('equipment_jops')
            ->with(['equipment_jops'])
            ->where('user_id', $user->id)
            ->where('sub_category_id', $sub_category_id)
            ->paginate(12)
            ->withQueryString();

        $records->setCollection(
            $records->getCollection()->flatMap(function ($item) {
                return $item->equipment_jops;
            })
        );

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'name' => $eqName,
            'image' => $eqImage,
            'requests' => $records
        ]);
    }

    public function getRequestsAndOffersNumber(Request $request, $jobType, $sub_category_id): JsonResponse
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

        $locale = $request->header('Accept-Language', 'en');

        $categoryModel = getModelClassFromType($jobType);

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $countOfRequests = collect($categoryModel)
            ->sum(fn($model) => $model::where([
                'sub_category_id' => $sub_category_id,
                'user_id' => $user->id,
            ])->count());

        $countOfOffers = NewProposal::query()
            ->whereHas('request', function ($query) use ($categoryModel, $user, $sub_category_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($user, $sub_category_id) {
                        $query->where('user_id', $user->id)
                            ->where('sub_category_id', $sub_category_id);
                    });
            })->count();

        return response()->json([
            'name' => $sub_category->getTranslatedName($locale),
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'count_of_requests' => $countOfRequests,
            'count_of_offers' => $countOfOffers,
        ]);
    }

    public function getRequestDetails(Request $request, $jobType, $sub_category_id, $request_id): JsonResponse
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

        $locale = $request->header('Accept-Language', 'en');
        $sub_category = SubCategory::findOrFail($sub_category_id);

        $categoryModel = getModelClassFromType($jobType);

        $data = $categoryModel::query()
            ->with('user:id,first_name,last_name,image')
            ->where('id', $request_id)
            ->where('sub_category_id', $sub_category_id)
            ->where('user_id', $user->id)
            ->first();

        if ($data && ($data->isSeen == 0)) {
            $data->update([
                'isSeen' => 1
            ]);
        }

        if ($data) $data['remaining_time'] = $this->getRemainingTimeForRequestAvailability($data->max_offer_deadline);

        if ($data && $data->user && $data->user->image) {
            $data->user->image = asset('storage/assets/uploads/users/' . $data->user->image);
        }

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'name' => $sub_category->getTranslatedName($locale),
            'request' => $data
        ]);
    }

    public function updateRequest(UpdateRequestRequest $request, string $jobType, int $id): JsonResponse
    {
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

        $jobModel = getModelClassFromType($jobType)::findOrFail($id);
        $jobModel->update($request->validated());

        return response()->json(
            [
                'message' => 'Request updated successfully',
                'request' => $jobModel
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

    private function getRemainingTimeForRequestAvailability($end_at)
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
