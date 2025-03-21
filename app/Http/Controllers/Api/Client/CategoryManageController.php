<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Modules\Service\Entities\Category;

class CategoryManageController extends Controller
{
    //get all category
    public function category(Request $request)
    {

        if (!empty($request->category)) {
            $category_list = Category::with(['sub_categories.sub_sub_categories'])
                ->select(['id', 'category', 'image'])
                ->where('status', 1)
                ->where('category', 'LIKE', "%" . strip_tags($request->category) . "%")
                ->paginate(10)
                ->withQueryString();
        } else {
            $category_list = Category::select(['id', 'category', 'image'])
                ->with('sub_categories.sub_sub_categories')
                ->where('status', 1)
                ->paginate(10)
                ->withQueryString();
        }

        if ($category_list) {
            return CategoryResource::collection($category_list);
        }

        return response()->json([
            'msg' => __('No category found'),
        ]);
    }
}
