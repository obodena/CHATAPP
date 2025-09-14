<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{from_type}.{from_id}.{to_type}.{to_id}', function ($user, $from_type, $from_id, $to_type, $to_id) {
    // authorize if the user is the sender or receiver
    // $user could be teacher or student, so check via guard or type
    return true;
});

// For presence channel to detect who is online
Broadcast::channel('online-users', function ($user) {
    // return user info to client
    return ['id' => $user->id, 'name' => $user->name, 'type' => get_class($user)];
});
Broadcast::channel('online-users', function ($user) {
    return ['id' => $user->id, 'name' => $user->name, 'type' => class_basename($user)];
});
