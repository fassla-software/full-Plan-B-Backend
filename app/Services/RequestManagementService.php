<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\PushNotificationTrait;

class RequestManagementService
{
    use PushNotificationTrait;

    function pushNotification($request, $subCategory)
    {
        $tokens = $this->getAllAvailableUsersForSendNotiication($request, $subCategory);
        $title = "New Request Available!";
        $body = "A user is looking for services like yours. Don’t miss the chance—send your offer now!";
        $data = [
            "request" => $request,
        ];

        return $this->sendMulticastNotification($tokens, $title, $body, $data);
    }

    function getAllAvailableUsersForSendNotiication($request, $subCategory): array
    {
        $nearbyEquipments = getEquipmentModelFromType($subCategory)::with('user')
            ->where('user_id', '<>', $request->user_id)
            ->where('sub_category_id', $request->sub_category_id)
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->whereRaw('
        (6371 * acos(cos(radians(?)) * cos(radians(lat)) * 
        cos(radians(`long`) - radians(?)) + sin(radians(?)) * 
        sin(radians(lat)))) <= ?
    ', [
                $request->lat,
                $request->long,
                $request->lat,
                $request->search_radius
            ])
            ->get();


        $firebaseTokens = $nearbyEquipments
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->map(function ($user) {
                return $user->routeNotificationForFcm();
            })
            ->filter()
            ->values()
            ->toArray();

        return $firebaseTokens;
    }
}
