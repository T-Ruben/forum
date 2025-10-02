<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function show(Thread $thread)
    {
        $thread->load(['latestPost.user',]);


        $posts = $thread->posts()
            ->with(['user' => function ($query) {
                $query->withCount('posts');
            }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('threads.show', [
            'thread' => $thread,
            'posts' => $posts,
        ]);
    }
}
