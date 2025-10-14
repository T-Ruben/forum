<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [ForumController::class, 'index'])
    ->name('home');


// Forums
Route::get('forums/{forum:slug}', [ForumController::class, 'show'])
    ->name('forums.show');


// Threads
Route::get('threads/{thread}/{slug}', [ThreadController::class, 'show'])
    ->name('threads.show');

Route::get('forums/{forum:slug}/create', [ThreadController::class, 'create'])
    ->middleware('auth')
    ->name('threads.create');

Route::post('forums/{forum:slug}/threads', [ThreadController::class, 'store'])
    ->middleware('auth')
    ->name('threads.store');


// Posts
Route::post('/posts', [PostController::class, 'store'])
    ->middleware('auth')
    ->name('posts.store');


// Auth routes
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.store');

Route::get('/login', [SessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [SessionController::class, 'store'])
    ->name('login.store');

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout.destroy');



// User
Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');
