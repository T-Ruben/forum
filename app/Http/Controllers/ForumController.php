<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Post;
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

        $forums = Forum::with(['threads' ,'latestThread', 'latestThread.user', 'threads.latestPost',
            'threads.latestPost.user'])
            ->withCount(['threads', 'posts'])
            ->get();

        // $forums = Forum::withCount(['threads', 'posts'])->get();


        $forumsCategory = ForumCategory::with(['forums' => function ($query) {
            $query->limit(5)
                ->withCount(['threads', 'posts'])
                ->with(['latestThread.latestPost', 'latestThread.latestPost.user', 'latestThread.user', 'latestActiveThread.latestPost.user'
            ]);
        }])->get();

        return view('home', [
            'forums' => $forums,
            'forumsCategory' => $forumsCategory,
        ]);
    }
}
