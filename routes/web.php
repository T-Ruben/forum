<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Post;
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

 Route::get('threads/{thread:slug}', [ThreadController::class, 'show']);

Route::post('/posts', [PostController::class, 'store']);
