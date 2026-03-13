<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function getNotifications(User $user, int $perPage = 10)
    {
        $notifications = $user->notifications()->latest()->paginate($perPage);

        $conversationIds = $notifications
            ->pluck('data.conversation_id')
            ->filter()
            ->unique();

        $inviterIds = $notifications
            ->pluck('data.inviter_id')
            ->filter()
            ->unique();

        $invitationIds = $notifications
            ->pluck('data.invitation_id')
            ->filter()
            ->unique();

        $inviters = User::whereIn('id', $inviterIds)->get()->keyBy('id');

        $conversations = Conversation::withCount('users')
            ->whereIn('id', $conversationIds)
            ->get()
            ->keyBy('id');

        $invitations = ConversationInvitation::with(['conversation.users', 'inviter'])
            ->whereIn('id', $invitationIds)
            ->get()
            ->keyBy('id');

        return [
            'notifications' => $notifications,
            'conversations' => $conversations,
            'inviters' => $inviters,
            'invitations' => $invitations,
        ];
    }
}
