<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Homepage - shows forum categories
Route::get('/', [ForumController::class, 'index']);

// Individual forum - shows threads in that forum
Route::get('forums/{forum:slug}', [ForumController::class, 'show']);

// Individual thread - shows posts in that thread
Route::get('threads/{thread}/{slug}', [ThreadController::class, 'show']);

// Create posts
Route::post('/posts', [PostController::class, 'store'])->middleware('auth');

// Auth routes
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'create'])->middleware('guest');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');


// User
Route::get('/users/{user}', [UserController::class, 'show']);
