<?php

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

Broadcast::channel('lists.{token}', function ($user, $token) {

    return true;
    //@TODO implement some logic here later

});

Broadcast::channel('lists.{id}', function ($user, $id) {

    return true;
    //@TODO implement some logic here later

});

Broadcast::channel('comments.{relatedListsId}', function ($user, $relatedListsId) {

    return true;
    //@TODO implement some logic here later

});