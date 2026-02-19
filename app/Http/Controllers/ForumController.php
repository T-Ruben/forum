<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;


class ForumController extends Controller
{
        public function show(Forum $forum) {
        $threads = $forum->threads()
            ->with(['user', 'posts', 'latestPost', 'latestPost.user'])
            ->withCount('posts')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('forums.show', [
            'forum' => $forum,
            'threads' => $threads
        ]);
    }

    public function index() {

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
        ->latest()
        ->take(5)
        ->get();

        return view('home', [
            'forumsCategory' => $forumsCategory, 'forumPosts' => $forumPosts, 'profilePosts' => $profilePosts,
        ]);
    }
}
