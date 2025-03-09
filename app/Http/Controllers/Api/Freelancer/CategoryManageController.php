<?php

namespace App\Http\Controllers\Api\Freelancer;

use Illuminate\Http\Request;
use App\Models\VehicleRental;
use App\Models\CraneRentalJob;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentJob;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\Category;
use App\Http\Resources\CategoryResource;
use Modules\Service\Entities\SubCategory;

class CategoryManageController extends Controller
{
    public function category(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        App::setLocale($locale);

        $query = Category::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->where('status', 1);

        if (!empty($request->category)) {
            $query->whereHas('translations', function ($q) use ($request, $locale) {
                $q->where('locale', $locale)->where('name', 'LIKE', "%" . strip_tags($request->category) . "%");
            });
        }

        return CategoryResource::collection($query->paginate(10)->withQueryString());
    }

    public function getAllRequestsOfEquipment(Request $request, $category_id, $sub_category_id)
    {
        $userId = auth('sanctum')?->user()?->id;
        $locale = $request->header('Accept-Language', 'en');
        $sub_category = SubCategory::findOrFail($sub_category_id);

        $data = collect();

        foreach ([HeavyEquipmentJob::class, CraneRentalJob::class] as $model) {
            $records = $model::query()
                ->where('user_id', $userId)
                ->where('category_id', $category_id)
                ->where('sub_category_id', $sub_category_id)
                ->get();

            $data = $data->merge($records);
        }

        return response()->json([
            'category_id' => $category_id,
            'sub_category_id' => $sub_category_id,
            'name' => $sub_category->getTranslatedName($locale),
            'requests' => $data
        ]);
    }

    public function getRequestsAndOffersNumber(Request $request, $category_id, $sub_category_id)
    {
        $userId = auth('sanctum')?->user()?->id;
        $locale = $request->header('Accept-Language', 'en');

        $countOfRequests = 0;

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $countOfRequests = collect([HeavyEquipmentJob::class, CraneRentalJob::class])
            ->sum(fn($model) => $model::where([
                'user_id' => $userId,
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id
            ])->count());

        return response()->json([
            'name' => $sub_category->getTranslatedName($locale),
            'category_id' => $category_id,
            'sub_category_id' => $sub_category_id,
            'count_of_requests' => $countOfRequests,
            'count_of_offers' => 0,
        ]);
    }
}
