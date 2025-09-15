<?php

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Thread;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $forums = Forum::all();
    $forumsCategory = ForumCategory::with(['forums' => function ($query) {
        $query->limit(5);
    }])->get();

    return view('home', [
        'forums' => $forums,
        'forumsCategory' => $forumsCategory,
    ]);
});


Route::get('forums/{forum:slug}', function (Forum $forum) {
    return view('forums.show', ['forum' => $forum]);
});

Route::get('threads/{thread:slug}', function (Thread $thread) {
    return view('threads.show', ['thread' => $thread]);
});
