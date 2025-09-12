<?php

use App\Models\Forum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $forums = Forum::all();

    return view('home', ['forums' => $forums]);
});

Route::get('/forums/{slug}', function ($slug) {
    $forum = Forum::where('slug', $slug)->firstOrFail();

    return view('forums', ['forum' => $forum]);
});
