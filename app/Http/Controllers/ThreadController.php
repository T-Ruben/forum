<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function show(Thread $thread) {
        $thread->load([
            'posts.user' => function ($query) {
            $query->withCount('posts');
        }]);

        return view('threads.show', ['thread' => $thread]);
    }
}
