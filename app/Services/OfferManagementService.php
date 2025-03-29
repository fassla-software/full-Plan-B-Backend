<?php

namespace App\Services;

use App\Models\User;
use App\Models\NewProposal;
use App\Traits\PushNotificationTrait;

class OfferManagementService
{
    use PushNotificationTrait;

    function pushNotification(User $recipientUser, NewProposal $proposal)
    {
        $userToken = $recipientUser->routeNotificationForFcm();
        $title = "there are new offer on your request!";
        $body = "Please click here to get the offer details!";
        $data = [
            "offer" => $proposal,
            "user" => $recipientUser,
        ];

        return $this->sendNotification($userToken, $title, $body, $data);
    }
}
