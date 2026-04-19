<?php

namespace App\Http\Controllers;

use App\Actions\CreatePostAction;
use App\Actions\DeletePostAction;
use App\Actions\UpdatePostAction;
use App\Http\Requests\Post\StoreRequest;
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
    public function store(StoreRequest $request, Thread $thread) {
    Gate::authorize('create', Post::class);

    $validated = $request->validated();

    try {

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
