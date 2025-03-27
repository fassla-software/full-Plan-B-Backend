<?php

namespace App\Http\Controllers\Api\Freelancer;

use DateTime;
use Carbon\Carbon;
use App\Enums\MachineType;
use App\Models\NewProposal;
use App\Enums\OperationType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\SubCategory;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Requests\requests\UpdateRequestRequest;
use App\Http\Requests\CategoryRequest\CraneRentJobRequest;
use App\Http\Requests\CategoryRequest\GeneratorJobRequest;
use App\Http\Requests\CategoryRequest\ScaffoldingJobRequest;
use App\Http\Requests\CategoryRequest\VehicleRentJobRequest;
use App\Http\Requests\CategoryRequest\HeavyEquipmentJobRequest;

class RequestsManageController extends Controller
{
    public function addRequest(Request $request, $subCategory, $subSubCategory)
    {

        DB::beginTransaction();

        try {
            // Validate data using the specific request class
            $requests = [
                MachineType::heavyEquipment->value => HeavyEquipmentJobRequest::class,
                MachineType::vehicleRental->value => VehicleRentJobRequest::class,
                MachineType::craneRental->value => CraneRentJobRequest::class,
                MachineType::generatorRental->value => GeneratorJobRequest::class,
                MachineType::scaffoldingToolsRental->value => ScaffoldingJobRequest::class,
                // Add other sub-category request classes here
            ];

            if (!isset($requests[$subCategory])) {
                return response()->json(['error' => 'Sub-category not found'], 404);
            }

            $booleanFields = [
                'safety_compliant',
                'environmental_compliant',
                'has_night_lighting',
            ];

            foreach ($booleanFields as $field) {
                if (isset($request[$field]) && is_string($request[$field])) {
                    $request[$field] = json_decode($request[$field], true);
                }
            }

            $request['equipment_type'] = $subSubCategory;

            //$request['name'] = ucfirst($subSubCategory);

            // Resolve and validate using the specific request class
            $validatedData = app($requests[$subCategory])->validated();
            $user = auth('sanctum')->user();
            $validatedData['user_id'] = $user->id;

            // Map sub-category to model
            $models = [
                MachineType::heavyEquipment->value => \App\Models\HeavyEquipmentJob::class,
                MachineType::vehicleRental->value => \App\Models\VehicleRentalJob::class,
                MachineType::craneRental->value => \App\Models\CraneRentalJob::class,
                MachineType::generatorRental->value => \App\Models\GeneratorRentalJop::class,
                MachineType::scaffoldingToolsRental->value => \App\Models\ScaffoldingAndMetalFormworkRentalJob::class,
                // Add other sub-category models here
            ];
            $model = $models[$subCategory];
            $model::create($validatedData);

            $currentSubscripiton = getCurrentUserSubsicription($user);
            if ($currentSubscripiton) {
                minusUserAvailableLimit($currentSubscripiton, OperationType::makeRequest);
            }

            DB::commit();

            return response()->json([
                'message' => ucfirst(str_replace('_', ' ', $subCategory)) . ' data saved successfully!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'There was an error processing your request. Please try again. ' . $e->getMessage()
            ], 500);
        }
    }

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

        $userEquipments = getEquipmentModelFromType($jobType)::select(['id', 'lat', 'long'])
            ->where('user_id', $user->id)
            ->where('sub_category_id', $subCategory->id)
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

        $records = $jobModel::with(['user:id,first_name,last_name'])
            ->where('sub_category_id', $subCategory->id)
            ->where('user_id', '<>', $user->id)
            ->whereDate('max_offer_deadline', '>=', Carbon::today())
            ->where(function ($query) use ($distanceConditions) {
                foreach ($distanceConditions as $condition) {
                    $query->orWhereRaw($condition);
                }
            })
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

        $userEquipments = getEquipmentModelFromType($jobType)::select(['id', 'lat', 'long'])
            ->where('user_id', $user->id)
            ->where('sub_category_id', $subCategory->id)
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

        $countOfRequests = $categoryModel::where('sub_category_id', $subCategory->id)
            ->where('user_id', '<>', $user->id)
            ->whereDate('max_offer_deadline', '>=', Carbon::today())
            ->where(function ($query) use ($distanceConditions) {
                foreach ($distanceConditions as $condition) {
                    $query->orWhereRaw($condition);
                }
            })->count();

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
        $data['offer'] = $data->request?->newProposals
            ->where('user_id', $user->id)->first();

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

        $user = auth('sanctum')->user();
        $currentSubscripiton = getCurrentUserSubsicription($user);
        if ($currentSubscripiton) {
            minusUserAvailableLimit($currentSubscripiton, OperationType::updateRequest);
        }

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
