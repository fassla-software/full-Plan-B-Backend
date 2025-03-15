<?php

namespace App\Http\Controllers\Api\Freelancer;

use DateTime;
use App\Enums\MachineType;
use App\Models\NewProposal;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\SubCategory;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Requests\requests\UpdateRequestRequest;

class RequestsManageController extends Controller
{
    public function getAllRequestsOfEquipment(Request $request, string $jobType, SubCategory $subCategory): JsonResponse
    {
        if (!MachineType::tryFrom($jobType)) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => ['jobType' => 'Invalid job type']
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $subCategory->loadMissing('translations');
        $locale = $request->header('Accept-Language', 'en');
        $eqName = $subCategory->getTranslatedName($locale);
        $eqImage = $this->getFullImageUrl($subCategory->image);

        $jobModel = getModelClassFromType($jobType);

        $records = $jobModel::with(['user:id,first_name,last_name'])
            ->where('sub_category_id', $subCategory->id)
            ->where('user_id', '<>', $user->id)
            ->paginate(12);

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $subCategory->id,
            'name' => $eqName,
            'image' => $eqImage,
            'requests' => $records
        ]);
    }

    public function getRequestsAndOffersNumber(Request $request, string $jobType, SubCategory $subCategory): JsonResponse
    {
        if (!MachineType::tryFrom($jobType)) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => ['jobType' => 'Invalid job type']
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $locale = $request->header('Accept-Language', 'en');
        $categoryModel = getModelClassFromType($jobType);

        $countOfRequests = $categoryModel::where('sub_category_id', $subCategory->id)
            ->where('user_id', '<>', $user->id)->count();

        $countOfOffers = NewProposal::whereHas('request.requestable', function ($query) use ($categoryModel, $user, $subCategory) {
            $query->where('user_id', $user->id)
                ->where('sub_category_id', $subCategory->id)
                ->where('requestable_type', $categoryModel);
        })->count();

        return response()->json([
            'name' => $subCategory->getTranslatedName($locale),
            'category_slug' => $jobType,
            'sub_category_id' => $subCategory->id,
            'count_of_requests' => $countOfRequests,
            'count_of_offers' => $countOfOffers,
        ]);
    }

    public function getRequestDetails(Request $request, string $jobType, SubCategory $subCategory, int $request_id): JsonResponse
    {
        if (!MachineType::tryFrom($jobType)) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => ['jobType' => 'Invalid job type']
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $locale = $request->header('Accept-Language', 'en');
        $categoryModel = getModelClassFromType($jobType);

        $data = $categoryModel::with(['user:id,first_name,last_name,image', 'request.newProposals'])
            ->findOrFail($request_id);

        if ($data->isSeen == 0) {
            $data->update(['isSeen' => 1]);
        }

        $data['remaining_time'] = $this->getRemainingTimeForRequestAvailability($data->max_offer_deadline);
        $data['offer_id'] = $data->request?->newProposals
            ->where('user_id', $user->id)->first()?->id;

        if ($data->user && $data->user->image) {
            $data->user->image = asset('storage/assets/uploads/users/' . $data->user->image);
        }

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $subCategory->id,
            'name' => $subCategory->getTranslatedName($locale),
            'request' => $data
        ]);
    }

    public function updateRequest(UpdateRequestRequest $request, string $jobType, int $id): JsonResponse
    {
        if (!MachineType::tryFrom($jobType)) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => ['jobType' => 'Invalid job type']
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
