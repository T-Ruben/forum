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
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class PostController extends Controller
{
    public function store(StoreRequest $request, PostService $service) {
    Gate::authorize('create', Post::class);

    $validated = $request->validated();

    try {
        $service->store($validated);

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
