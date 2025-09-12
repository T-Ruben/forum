<?php

use App\Models\Forum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $forums = Forum::all();

    return view('home', ['forums' => $forums]);
});


Route::get('forums/{forum:slug}', function (Forum $forum) {
    return view('forums.forums', ['forum' => $forum]);
});
