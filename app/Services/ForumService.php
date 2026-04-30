<?php

namespace App\Services;

use App\Models\ForumCategory;
use App\Models\Post;

class ForumService
{
    /**
     * Create a new class instance.
     */
    public function index()
    {
        $forumsCategory = ForumCategory::with(['forums' => function ($query) {
            $query->withCount(['threads', 'posts'])
                ->with(['latestThread.latestPost', 'latestThread.latestPost.user', 'latestThread.user', 'latestActiveThread.latestPost.user'
            ]);
        }])->get();

        $forumPosts = Post::with(['user', 'thread.forum', 'parent'])
        ->whereNotNull('thread_id')
        ->latest()
        ->take(5)
        ->get();

        $profilePosts = Post::with(['user', 'profileOwner', 'parent'])
        ->whereNull('thread_id')
        ->whereNull('parent_id')
        ->latest()
        ->take(5)
        ->get();

        return ['forumsCategory' => $forumsCategory, 'forumPosts' => $forumPosts, 'profilePosts' => $profilePosts];
    }
}
