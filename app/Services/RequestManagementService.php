<?php

namespace App\Services;

use App\Traits\PushNotificationTrait;
use App\Notifications\NewRequestNotification;

class RequestManagementService
{
    use PushNotificationTrait;

    function pushNotification($request, $subCategory)
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

        $users = $nearbyEquipments
            ->pluck('user')
            ->filter()
            ->unique('id');

        foreach ($users as $user) {
            $user->notify(new NewRequestNotification($request));
        }

        $tokens = $users
            ->map(function ($user) {
                return $user->routeNotificationForFcm();
            })
            ->filter()
            ->values()
            ->toArray();

        $title = "New Request Available!";
        $body = "A user is looking for services like yours. Don’t miss the chance—send your offer now!";
        $data = [
            "request_id" => $request->id,
            "sender_id" => $request->user_id
        ];

        return $this->sendMulticastNotification($tokens, $title, $body, $data);
    }
}
