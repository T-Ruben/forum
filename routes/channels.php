<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('forum.online', function(User $user) {
    return [
        'id' => $user->id,
        'name' => $user->name
    ];
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return Conversation::find($conversationId, 'id')
        ->users()
        ->where('user_id', $user->id)
        ->exists();
});
