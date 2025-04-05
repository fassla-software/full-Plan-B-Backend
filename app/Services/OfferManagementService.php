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
        $title = "You've Got a New Offer!";
        $body = "Your request just got a response! Open now to review the offer and move forward.";
        $data = [
            "offer" => $proposal,
            "user" => $recipientUser,
        ];

        return $this->sendNotification($userToken, $title, $body, $data);
    }
}
