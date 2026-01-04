<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Allow access if the authenticated user is the owner of the channel
    return (string) $user->id === (string) $userId;
});

