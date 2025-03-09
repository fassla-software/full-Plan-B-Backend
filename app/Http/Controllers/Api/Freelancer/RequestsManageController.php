<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Models\NewProposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\SubCategory;

class RequestsManageController extends Controller
{

    public function getAllRequestsOfEquipment(Request $request, $jobType, $sub_category_id)
    {
        $locale = $request->header('Accept-Language', 'en');
        $sub_category = SubCategory::findOrFail($sub_category_id);

        $categoryModel = $this->getModelClassFromType($jobType);

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
            ->get();

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'name' => $sub_category->getTranslatedName($locale),
            'requests' => $records
        ]);
    }

    public function getRequestsAndOffersNumber(Request $request, $jobType, $sub_category_id)
    {
        $userId = auth('sanctum')?->user()?->id;
        $locale = $request->header('Accept-Language', 'en');

        $countOfRequests = 0;

        $categoryModel = $this->getModelClassFromType($jobType);

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $countOfRequests = collect($categoryModel)
            ->sum(fn($model) => $model::where([
                'sub_category_id' => $sub_category_id
            ])->count());

        $countOfOffers = NewProposal::query()
            ->whereHas('request', function ($query) use ($categoryModel, $userId, $sub_category_id) {
                $query->where('requestable_type', $categoryModel)
                    ->whereHas('requestable', function ($query) use ($userId, $sub_category_id) {
                        $query->where('user_id', $userId)
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

    public function getRequestDetails(Request $request, $jobType, $sub_category_id, $request_id)
    {
        $locale = $request->header('Accept-Language', 'en');
        $sub_category = SubCategory::findOrFail($sub_category_id);

        $categoryModel = $this->getModelClassFromType($jobType);

        $data = $categoryModel::query()
            ->with('user:id,first_name,last_name')
            ->where('id', $request_id)
            ->where('sub_category_id', $sub_category_id)
            ->get();

        return response()->json([
            'category_slug' => $jobType,
            'sub_category_id' => $sub_category_id,
            'name' => $sub_category->getTranslatedName($locale),
            'request' => $data
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
