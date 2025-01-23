<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\StateResource;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class CountryManageController extends Controller
{
    //get all country
    public function country(Request $request)
    {
        if(!empty($request->country)){
            $country_list = Country::select('id','country')->where('status',1)
                ->where('country', 'LIKE', "%". strip_tags($request->country) ."%")
                ->paginate(10)->withQueryString();
        }else{
            $country_list = Country::select('id','country')->where('status',1)->paginate(10)->withQueryString();
        }

        if($country_list){
            return CountryResource::collection($country_list);
        }

        return response()->json([
            'msg'=> __('No country found'),
        ]);

    }

    //get all state by country
    public function state(Request $request)
    {
        $request->validate(['country_id'=>'required|integer']);

        if(!empty($request->state)){
            $state_list = State::select(['id','country_id','state'])
                ->where('country_id', $request->country_id)
                ->where('status',1)
                ->where('state', 'LIKE', "%". strip_tags($request->state) ."%")
                ->paginate(10)->withQueryString();
        }else{
            $state_list = State::select(['id','country_id','state'])
                ->where('country_id', $request->country_id)
                ->where('status',1)->paginate(10)
                ->withQueryString();
        }

        if($state_list){
            return StateResource::collection($state_list);
        }

        return response()->json([
            'msg'=> __('No state found'),
        ]);

    }

    //get all city by state
    public function city(Request $request)
    {
        $request->validate(['state_id'=>'required|integer']);

        if(!empty($request->city)){
            $city_list = City::select(['id','state_id','city'])
                ->where('state_id', $request->state_id)
                ->where('status',1)
                ->where('city', 'LIKE', "%". strip_tags($request->city) ."%")
                ->paginate(10)->withQueryString();
        }else{
            $city_list = City::select(['id','state_id','city'])
                ->where('state_id', $request->state_id)
                ->where('status',1)
                ->paginate(10)->withQueryString();
        }

        if($city_list){
            return CityResource::collection($city_list);
        }

        return response()->json([
            'msg'=> __('No city found'),
        ]);

    }
}
