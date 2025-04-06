<?php

namespace App\Services;

use App\Models\User;
use App\Models\NewProposal;
use App\Models\GeneratorOfferDetails;
use App\Models\ScaffoldingOfferDetails;
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

    function createOffer($validatedData, $modelClass): NewProposal
    {
        $proposal = NewProposal::create([
            'user_id' => $validatedData['user_id'],
            'request_id' => $validatedData['request_id'],
            'price' => $validatedData['price'],
            'per' => $validatedData['per'],
            'current_location' => $validatedData['current_location'],
            'offer_ends_at' => $validatedData['offer_ends_at'],
            'other_terms' => $validatedData['other_terms'],
        ]);

        if ($modelClass ==  \App\Models\GeneratorRentalJop::class) {
            $generatorOfferDetails = GeneratorOfferDetails::create([
                'model' => $validatedData['model'],
                'generator_power' => $validatedData['generator_power'],
                'max_number_of_continues_operating_houres' => $validatedData['max_number_of_continues_operating_houres'],
                'number_of_daily_operating_houres' => $validatedData['number_of_daily_operating_houres'],
                'generator_images' => $validatedData['generator_images'],
            ]);

            $proposal->update([
                'generator_offer_detail_id' => $generatorOfferDetails->id,
            ]);
        } elseif ($modelClass ==  \App\Models\ScaffoldingAndMetalFormworkRentalJob::class) {
            $scaffoldingDetails = ScaffoldingOfferDetails::create([
                'time_required_for_on_site_installation' => $validatedData['time_required_for_on_site_installation'],
                'scaffolding_images' => $validatedData['scaffolding_images'],
            ]);

            $proposal->update([
                'scaffolding_offer_detail_id' => $scaffoldingDetails->id,
            ]);
        }

        return $proposal->load(['generatorOfferDetails', 'scaffoldingOfferDetails']);
    }
}
