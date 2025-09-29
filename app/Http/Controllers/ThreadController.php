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
        }])->paginate(10);

        return view('threads.show', ['thread' => $thread]);
    }
}
