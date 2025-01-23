<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\Country;
use Modules\Service\Entities\Category;

class CategoryManageController extends Controller
{
    //get all category
    public function category(Request $request)
    {
        if(!empty($request->category)){
            $category_list = Category::with('sub_categories')->select(['id','category'])->where('status',1)
                ->where('category', 'LIKE', "%". strip_tags($request->category) ."%")
                ->paginate(10)->withQueryString();
        }else{
            $category_list = Category::select(['id','category'])->with('sub_categories')->where('status',1)->paginate(10)->withQueryString();
        }

        if($category_list){
            return CategoryResource::collection($category_list);
        }

        return response()->json([
            'msg'=> __('No category found'),
        ]);

    }
}
