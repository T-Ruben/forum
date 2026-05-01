<?php

namespace App\Services;

use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ThreadService
{
    /**
     * Create a new class instance.
     */
    public function threadService(array $filter, Thread $thread)
    {
        $replyId = $filter['reply'];
        $editId = $filter['edit'];

        $editPost = null;
        $replyTo = null;

        if($editId) {
            $editPost = Post::where('thread_id', $thread->id)
                ->findOrFail($editId);

            Gate::authorize('update', $editPost);
        }
            elseif($replyId)
        {
            $replyTo = Post::where('thread_id', $thread->id)
                ->findOrFail($replyId);
        }

        $thread->load(['latestPost.user']);

        $posts = $thread->posts()
            ->with(['user.following', 'parent.user', 'user.followers', 'user' => function ($query) {
                $query->withCount('posts', 'following', 'followers');
            }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return [
            'thread' => $thread,
            'posts' => $posts,
            'replyTo' => $replyTo,
            'editPost' => $editPost
        ];
    }

    public function createThread(Forum $forum, array $validated) {
        $thread = $forum->threads()->create([
            'title' => $validated['title'],
            'user_id' => Auth::user()->id,
        ]);

        $thread->posts()->create([
            'user_id' => Auth::user()->id,
            'content' => $validated['plain_content']
        ]);

        return [$thread, $thread->slug];
    }
}
