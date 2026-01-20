<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function store(Request $request) {
    $content = $request->input('content');
    $plain = trim(strip_tags($content));

    if(strlen($plain) < 1) {
        return back()->withErrors(['content' => 'Must have at least one character.']);
    }

    if(strlen($plain) > 5000) {
        return back()
        ->withInput()
        ->withErrors(['content' => 'Must have less than 5000 characters.']);
    }

    $validated = $request->validate([
        'content' => ['required', 'string'],
        'thread_id' => 'required|exists:threads,id',
        'parent_id' => 'nullable|exists:posts,id'
    ]);

    try {
        $validated['content'] = trim($validated['content']);

        Auth::user()->posts()->create($validated);
        return back()->with('success', 'Post created successfully!');
    } catch (\Exception $e) {
        Log::error('Post creation failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
        ]);
        return back()
            ->withErrors(['content' => 'Something went wrong.'])
            ->withInput();
        } catch (\Exception $e) {
            Log::error('Post creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['content' => 'Something went wrong. Please try again later.'])
                ->withInput();
        }
    }

    public function storeProfile(Request $request, User $user) {
    $content = $request->input('content');
    $plain = trim(strip_tags($content));

    if(strlen($plain) < 1) {
        return back()->withErrors(['content' => 'Must have at least one character.']);
    }

    if(strlen($plain) > 1000) {
        return back()
        ->withInput()
        ->withErrors(['content' => 'Must have less than 1000 characters.']);
    }

    $validated = $request->validate([
        'content' => ['required', 'string'],
    ]);

    try {
        $validated['content'] = trim($validated['content']);
        Post::create([
            'user_id' => Auth::id(),
            'profile_user_id' => $user->id,
            'content' => $validated['content'],
            'type' => 'profile',
        ]);
        return back()->with('success', 'Post created successfully!');
    } catch (\Exception $e) {
        Log::error('Post creation failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
        ]);
        return back()
            ->withErrors(['content' => 'Something went wrong.'])
            ->withInput();
        } catch (\Exception $e) {
            Log::error('Post creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['content' => 'Something went wrong. Please try again later.'])
                ->withInput();
        }
    }
}
