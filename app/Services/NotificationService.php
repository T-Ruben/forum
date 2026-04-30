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
use Auth;

class NotificationService
{
    /**
     * Create a new class instance.
     */

    protected static array $requestCache = [];

    public function getNotifications(User $user, int $perPage = 10, $type = null)
    {
        $cacheKey = "user_notifs_" . $user->id . "_" . $perPage . "_" . ($type ?? 'all');

        if(isset(static::$requestCache[$cacheKey])) {
            return static::$requestCache[$cacheKey];
        }


        $user->loadCount([
            'unreadNotifications as total' => fn ($q) => $q->limit(100),
            'unreadNotifications as profile' => fn ($q) => $q->where('type', ProfilePostNotification::class)->limit(100),
            'unreadNotifications as thread' => fn ($q) => $q->where('type', ThreadPostNotification::class)->limit(100),
            'unreadNotifications as convMessage' => fn ($q) => $q->where('type', ConversationMessageNotification::class)->limit(100),
            'unreadNotifications as convInvite' => fn ($q) => $q->where('type', ConversationInvitation::class)->limit(100),
        ]);

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

        $result = [
            'user' => $user,
            'notifications' => $notifications,
            'invitations' => $invitations
        ];

        static::$requestCache[$cacheKey] = $result;

        return $result;
    }

    public function jump(object $post) {
        $page = $post->getPageNumberProfile();

         Auth::user()->unreadNotifications()
            ->whereJsonContains('data', ['post_id' => $post->id])
            ->get()
            ->markAsRead();

        return ['user' => $post->profile_user_id, 'page' => $page, 'highlight' => $post->id];
    }

}
