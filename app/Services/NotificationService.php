<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ConversationMessageNotification;
use App\Notifications\ProfilePostNotification;
use App\Notifications\ThreadPostNotification;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function getNotifications(User $user, int $perPage = 10, $type = null)
    {
        $user->loadCount([
            'unreadNotifications as total' => fn ($q) => $q->limit(100),
            'unreadNotifications as profile' => fn ($q) => $q->where('type', ProfilePostNotification::class)->limit(100),
            'unreadNotifications as thread' => fn ($q) => $q->where('type', ThreadPostNotification::class)->limit(100),
            'unreadNotifications as convMessage' => fn ($q) => $q->where('type', ConversationMessageNotification::class)->limit(100),
            'unreadNotifications as convInvite' => fn ($q) => $q->where('type', ConversationInvitation::class)->limit(100),
        ]);

        // IMPORTANT: as i will end up creating far too many vars for different notifs like this, i might need to
        // try to find another of making this more efficient. If i can't find any, i'll try remaking this with AI.

        $query = $user->notifications()->latest();


        if($type) {
            $query->where('type', $type);
        }

        $notifications = $query->paginate($perPage);

        $invitationIds = $notifications->getCollection()
        ->pluck('data.invitation.id')
        ->filter()
        ->unique();

        $invitations = ConversationInvitation::whereIn('id', $invitationIds)
        ->get()
        ->keyBy('id');

        return [
            'user' => $user,
            'notifications' => $query->latest()->paginate($perPage),
            'invitations' => $invitations
        ];
    }

}
