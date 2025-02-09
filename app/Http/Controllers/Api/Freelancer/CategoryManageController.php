<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Modules\Service\Entities\Category;
use Illuminate\Support\Facades\App;

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
                $q->where('locale', $locale)->where('name', 'LIKE', "%". strip_tags($request->category) ."%");
            });
        }

        return CategoryResource::collection($query->paginate(10)->withQueryString());
    }
}

