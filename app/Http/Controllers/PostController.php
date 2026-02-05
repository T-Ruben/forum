<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        'profile_user_id' => 'required|exists:posts,profile_user_id',
        'parent_id' => 'nullable|exists:posts,id'
    ]);

    try {
        $validated['content'] = trim($validated['content']);
                Auth::user()->posts()->create($validated);

        return back()
            ->with('success', 'Post created successfully!');
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
    public function update(Post $post, Request $request) {
    Gate::authorize('update', $post);

    $content = $request->input('content');
    $plain = trim(strip_tags($content));
    $isProfile = $request->routeIs('profile.post.update');
    $owner = $post->profile_user_id;
    $targetPost = $post->parent_id ? $post->parent : $post;
    $page = $post->getPageNumber();
    $pageProfile = $targetPost->getPageNumberProfile();
    $thread = $post->thread_id;
    $thread_slug = $post->thread?->slug;

    $minLength = $isProfile ? 1 : 1;
    $maxLength = $isProfile ? 1000 : 5000;

    if(strlen($plain) < $minLength) {
        return back()->withErrors(['content' => "Must have at least $minLength character."]);
    }

    if(strlen($plain) > $maxLength) {
        return back()
        ->withInput()
        ->withErrors(['content' => "Must have less than $maxLength characters."]);
    }

    $validated = $request->validate([
        'content' => ['required', 'string', "min:$minLength", "max:$maxLength"],
    ]);

    try {
        $post->update(['content' => trim($validated['content'])]);
        return $isProfile ? redirect()->route('users.show', ['user' => $owner, 'page' => $pageProfile]) :
            redirect()->route('threads.show', ['thread' => $thread, 'slug' => $thread_slug, 'page' => $page]);
    } catch(\Exception $e) {
        Log::error('Editing failed: ', ['error', $e->getMessage()]);
        return back()
            ->withErrors(['error' => 'Something went wrong while editing. Please try again.'])
            ->withInput();
    };

    }

    public function destroy(Post $post, Request $request) {
        Gate::authorize('delete', $post);

        $isProfile = $request->routeIs('profile.post.destroy');
        $owner = $post->profile_user_id;
        $page = $post->getPageNumber();
        $thread = $post->thread_id;
        $thread_slug = $post->thread?->slug;


        $post->delete();
        return $isProfile ? redirect()->route('users.show', ['user' => $owner, 'page' => $page]) :
                redirect()->route('threads.show', ['thread' => $thread, 'slug' => $thread_slug, 'page' => $page]);
    }
}
