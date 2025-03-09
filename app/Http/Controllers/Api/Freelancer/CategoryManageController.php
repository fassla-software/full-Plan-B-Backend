<?php

namespace App\Http\Controllers\Api\Freelancer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Modules\Service\Entities\Category;
use App\Http\Resources\CategoryResource;

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
}
