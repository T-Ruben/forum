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
            'threads.user'
        ]);

        return view('forums.show', ['forum' => $forum]);
    }

    public function index() {
        $forums = Forum::withCount('threads')
            ->latest()
            ->get();

        $forumsCategory = ForumCategory::with(['forums' => function ($query) {
            $query->limit(5)
                ->withCount('threads');
        }])->get();

        return view('home', [
            'forums' => $forums,
            'forumsCategory' => $forumsCategory,
        ]);
    }
}
