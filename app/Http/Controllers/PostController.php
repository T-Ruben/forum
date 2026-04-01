<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ProfilePostNotification;
use App\Notifications\ThreadPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread) {
    Gate::authorize('create', Post::class);

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

        $post = Auth::user()->posts()->create($validated);

        $userOwner = $post->thread->user;
        $userReply = $post->parent?->user;


        if($post->parent) {
            $userReply->notify(new ThreadPostNotification($post, $type = 'reply'));

            if($post->user_id !== $post->thread->user_id && $post->parent->user_id !== $post->thread->user_id) {
                $userOwner->notify(new ThreadPostNotification($post));
            }
        } elseif($post->user_id !== $post->thread->user_id) {
            $userOwner->notify(new ThreadPostNotification($post));
        }



        return back()->with('success', 'Post created successfully!');
    } catch (\Exception $e) {
        Log::error('Post creation failed', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
        ]);
        return back()
            ->withErrors(['content' => 'Something went wrong.'])
            ->withInput();
        }
    }

    public function storeProfile(Request $request, User $user, Post $post) {
    Gate::authorize('create', Post::class);

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
        'profile_user_id' => 'required|exists:users,id',
        'parent_id' => 'nullable|exists:posts,id'
    ]);

    try {
        $validated['content'] = trim($validated['content']);
        $replyToPost = null;

        if ($request->parent_id) {
            $replyToPost = Post::findOrFail($request->parent_id);

            $validated['parent_id'] = $replyToPost->parent_id ?? $replyToPost->id;
        }

        $post = Auth::user()->posts()->create($validated);


        if($post->parent && $post->user_id !== $post->parent->user_id) {
            $post->parent->user->notify(new ProfilePostNotification($post, $type = 'reply'));
        } elseif($post->user_id !== $post->parent?->user_id && $user->id !== $post->user_id) {
            $user->notify(new ProfilePostNotification($post));
        }

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
    $targetPost = $post->parent_id ? $post->parent : $post;
    $page = $post->getPageNumber();
    $pageProfile = $targetPost->getPageNumberProfile();
    $thread = $post->thread_id;
    $thread_slug = $post->thread?->slug;

        $post->update([
            'content' => '[deleted]',
        ]);
        $post->delete();
        return $isProfile ? redirect()->route('users.show', ['user' => $owner, 'page' => $pageProfile]) :
                redirect()->route('threads.show', ['thread' => $thread, 'slug' => $thread_slug, 'page' => $page]);
    }
}
