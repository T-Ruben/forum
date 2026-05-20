<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SettingsService
{
    /**
     * Create a new class instance.
     */
    public function threadsService(string $sortOrder)
    {
        $query = Auth::user()->threads()->withCount(['posts']);

        [$column, $direction] = match ($sortOrder) {
            'latest_activity' => ['updated_at', 'desc'],
            'asc', 'oldest'   => ['created_at', 'asc'],
            'most_posts'   => ['posts_count', 'desc'],
            default           => ['created_at', 'desc'],
        };

        $userThreads = $query->orderBy($column, $direction)
            ->paginate(10)
            ->withQueryString();

        return [
            'user' => Auth::user(),
            'threads' => $userThreads,
            'currentSort' => $sortOrder
            ];
    }

    public function conversationsService(string $sortOrder) {
        $query = Auth::user()->conversations()->withCount(['messages', 'users']);

        [$column, $direction] = match ($sortOrder) {
            'latest_activity' => ['updated_at', 'desc'],
            'asc', 'oldest'   => ['created_at', 'asc'],
            'most_messages'   => ['messages_count', 'desc'],
            'most_members'    => ['users_count', 'desc'],
            default           => ['created_at', 'desc'],
        };

        $conversations = $query->orderBy($column, $direction)
            ->paginate(10)
            ->withQueryString();

            return [
            'user' => Auth::user(),
            'conversations' => $conversations,
            'currentSort' => $sortOrder
            ];
    }

    public function prepareNotifications(User $user) {
        return $user;
    }

}
