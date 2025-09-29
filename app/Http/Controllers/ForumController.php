<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumCategory;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function show(Forum $forum) {
        $forum->load([
            'threads',
            'threads.user',
            'threads.posts'
        ]);

        return view('forums.show', ['forum' => $forum]);
    }

    public function index() {

        $forums = Forum::with(['latestThread', 'latestThread.user'])
            ->withCount(['threads', 'posts'])
            ->get();

        // $forums = Forum::withCount(['threads', 'posts'])->get();


        $forumsCategory = ForumCategory::with(['forums' => function ($query) {
            $query->limit(5)
                ->withCount('threads', 'posts')
                ->with('threads', 'latestThread', 'latestThread.user');
        }])->get();

        return view('home', [
            'forums' => $forums,
            'forumsCategory' => $forumsCategory,
        ]);
    }
}
