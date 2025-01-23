<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('livechat-client-channel.{freelancer_id}.{client_id}', function ($client, $freelancer_id){
    return (int) $client->id === (int) $freelancer_id;
});

Broadcast::channel('livechat-freelancer-channel.{client_id}.{freelancer_id}', static function ($freelancer, $client_id){
    return (int) $freelancer->id === (int) $client_id;
});
