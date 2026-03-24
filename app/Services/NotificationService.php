<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadPostNotification;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function getNotifications(User $user, int $perPage = 10)
    {
        // IMPORTANT: as i will end up creating far too many vars for different notifs like this, i might need to
        // try to find another of making this more efficient. If i can't find any, i'll try remaking this with AI.

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

        $threadIds = $notifications
            ->pluck('data.thread_id')
            ->filter()
            ->unique();

        $senderIds = $notifications
            ->pluck('data.sender_id')
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

        $threads = Thread::with('posts')
            ->whereIn('id', $threadIds)
            ->get()
            ->keyBy('id');

        $senders = User::whereIn('id', $senderIds)->get()->keyBy('id');

        return [
            'notifications' => $notifications,
            'conversations' => $conversations,
            'inviters' => $inviters,
            'invitations' => $invitations,
        ];
    }

    public function dispatchNotifications(Post $post) {
            $recipients = collect();

            if ($post->isForumPost()) {
            $thread = $post->thread;

            // Priority: Direct Reply to a specific post
            if ($post->parent_id && $post->parent->user_id !== $post->user_id) {
                $recipients->put($post->parent->user_id, 'post_reply');
            }

            // Secondary: Thread Owner (if they aren't the one who got the reply)
            if ($thread->user_id !== $post->user_id && !$recipients->has($thread->user_id)) {
                $recipients->put($thread->user_id, 'thread_activity');
            }

            // Send Forum Notifications
            foreach ($recipients as $userId => $type) {
                \App\Models\User::find($userId)->notify(new ThreadPostNotification($post, $type));
            }
        }
    }

}
