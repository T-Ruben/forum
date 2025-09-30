<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function show(Thread $thread)
    {
        $thread->load([
            'posts.user',
            'latestPost.user',
        ]);


        $posts = $thread->posts()->with('user')->paginate(20);

        return view('threads.show', [
            'thread' => $thread,
            'posts' => $posts,
        ]);
    }
}
