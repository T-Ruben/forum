<?php

use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SettingsController;
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
    ->middleware(['auth', 'throttle:make-thread'])
    ->name('threads.store');

Route::delete('/threads/{thread}', [ThreadController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('threads.destroy');


// Posts
Route::post('/posts', [PostController::class, 'store'])
    ->middleware(['auth', 'throttle:make-post'])
    ->name('posts.store');

Route::delete('threads/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('auth')
    ->name('threads.post.destroy');
Route::delete('profile/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('auth')
    ->name('profile.post.destroy');

Route::put('profile/posts/{post}', [PostController::class, 'update'])
    ->middleware('auth')
    ->name('profile.post.update');
Route::put('thread/posts/{post}', [PostController::class, 'update'])
    ->middleware('auth')
    ->name('thread.post.update');

Route::post('/members/{user}/posts', [PostController::class, 'storeProfile'])
    ->middleware(['auth', 'throttle:make-post'])
    ->name('user.posts.store');


// Auth routes
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('throttle:register')
    ->name('register.store');

Route::get('/login', [SessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [SessionController::class, 'store'])
    ->name('login.store')
    ->middleware('throttle:login');

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout.destroy');



// User
Route::get('/members/{user}', [UserController::class, 'show'])
    ->name('users.show');
Route::get('/index/members', [UserController::class, 'index'])
    ->name('members.index');

Route::post('/members/{user}/follow', [UserController::class, 'follow'])
    ->name('users.follow')
    ->middleware(['auth', 'throttle:20,1']);
Route::post('/members/{user}/unfollow', [UserController::class, 'unfollow'])
    ->name('users.unfollow')
    ->middleware(['auth', 'throttle:20,1']);
Route::delete('/members/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

// User Avatar
Route::middleware('auth')->group(function () {
    Route::put('/avatar/{user}', [AvatarController::class, 'update'])
        ->middleware('throttle:avatar-update')
        ->name('avatar.update');

    Route::delete('/avatar/{user}', [AvatarController::class, 'destroy'])
        ->name('avatar.destroy');
});

// Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/personal', [SettingsController::class, 'personal'])->name('settings.personal');
    Route::get('/settings/privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::get('/settings/threads', [SettingsController::class, 'threads'])->name('settings.threads');
});

