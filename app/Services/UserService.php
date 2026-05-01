<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function prepareUserShow(User $user)
    {
        $user = $user->loadCount(['followers', 'following']);

        $following = $user->following()
            ->limit(4)
            ->get();

        $followers = $user->followers()
            ->limit(4)
            ->get();

        return [
            'user' => $user,
            'following' => $following,
            'followers' => $followers
            ];
    }

    public function prepareUserIndex(string $sortOrder) {
        $query = User::query()->with(['followers', 'following', 'posts'])->withCount(['followers', 'following', 'posts']);

        match($sortOrder) {
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $users = $query
            ->paginate(24)
            ->withQueryString();

        return [
            'users' => $users,
            'currentSort' => $sortOrder
            ];
    }

    public function prepareUserFollowing(User $user) {
        $following = $user->following()
            ->with(['followers', 'following', 'posts'])
            ->withCount('following', 'followers', 'posts')
            ->paginate(25);

        return ['following' => $following, 'user' => $user];
    }

    public function prepareUserFollowers(User $user) {
        $followers = $user->followers()
            ->with(['followers', 'following', 'posts'])
            ->withCount('following', 'followers', 'posts')
            ->paginate(25);

        return ['followers' => $followers, 'user' => $user];
    }
}
