<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Models\NewProposal;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use Modules\Service\Entities\SubCategory;

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

        $categoryModel = getModelClassFromType($jobType);

        $eqImage = $sub_category->image ? asset('storage/assets/uploads/sub-category/' . $sub_category->image) : null;

        $records = $categoryModel::query()
            ->select([
                'id',
                'sub_category_id',
                'user_id',
                'category_id',
                'work_site_location',
                'hour',
                'day',
                'month',
                'size'
            ])
            ->with(['user:id,first_name,last_name'])
            ->where('sub_category_id', $sub_category_id)
            ->where('user_id', $user->id)
            ->get()
            ->map(fn($item) => array_merge($item->toArray(), ['name' => $eqName, 'image' => $eqImage]));

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
            ->with('user:id,first_name,last_name')
            ->where('id', $request_id)
            ->where('sub_category_id', $sub_category_id)
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'name' => $sub_category->getTranslatedName($locale),
            'request' => $data
        ]);
    }
}
