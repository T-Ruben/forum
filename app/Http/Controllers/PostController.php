<?php

namespace App\Http\Controllers;

use App\Actions\CreatePostAction;
use App\Actions\DeletePostAction;
use App\Actions\UpdatePostAction;
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

    // A lot of refactoring took place here for the livewire profile page.
    // Storeprofile, delete, and update all were changed quite a lot and made cleaner.
    // App/Actions, with 3 files inside was made as well.

    public function storeProfile(Request $request, User $user, CreatePostAction $action)
    {
        Gate::authorize('create', Post::class);

        $data = $request->validate([
            'content' => ['required', 'string'],
            'profile_user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:posts,id',
        ]);

        try {
            $action->execute($user, $data);

            return back()->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['content' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Post $post, Request $request, UpdatePostAction $action)
    {
        Gate::authorize('update', $post);

        $isProfile = $request->routeIs('profile.post.update');

        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        try {
            $action->execute(
                $post,
                $data,
                $isProfile ? 'profile' : 'thread'
            );

            return $isProfile
                ? redirect()->route('users.show', ['user' => $post->profile_user_id, 'page' => $post->getPageNumberProfile()])
                : redirect()->route('threads.show', [
                    'thread' => $post->thread_id,
                    'slug' => $post->thread?->slug,
                    'page' => $post->getPageNumber()
                ]);

        } catch (\Exception $e) {
            return back()
                ->withErrors(['content' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Post $post, Request $request, DeletePostAction $action)
    {
        Gate::authorize('delete', $post);

        $isProfile = $request->routeIs('profile.post.destroy');

        $action->execute($post);

        return $isProfile
            ? redirect()->route('users.show', ['user' => $post->profile_user_id, 'page' => $post->getPageNumberProfile()])
            : redirect()->route('threads.show', [
                'thread' => $post->thread_id,
                'slug' => $post->thread?->slug,
                'page' => $post->getPageNumber()
            ]);
    }
}
