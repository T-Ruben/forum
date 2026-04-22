<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;

class ThreadController extends Controller
{
    public function show(Thread $thread, Request $request)
    {
        $replyTo = null;
        $editPost = null;

        if($request->filled('edit_post')) {
            $editPost = Post::where('thread_id', $thread->id)
                ->findOrFail($request->edit_post);

            Gate::authorize('update', $editPost);
        }
            elseif($request->filled('reply_to'))
        {
            $replyTo = Post::where('thread_id', $thread->id)
                ->findOrFail($request->reply_to);
        }

        $thread->load(['latestPost.user']);


        $posts = $thread->posts()
            ->with(['user.following', 'parent.user', 'user.followers', 'user' => function ($query) {
                $query->withCount('posts', 'following', 'followers');
            }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('threads.show', [
            'thread' => $thread,
            'posts' => $posts,
            'replyTo' => $replyTo,
            'editPost' => $editPost
        ]);
    }

    public function create(Forum $forum) {
        return view('threads.create', ['forum' => $forum]);
    }

    public function store(Request $request, Forum $forum){
        Gate::authorize('create', [Thread::class, $forum]);
        $content = $request->input('content');

        $plain = trim(strip_tags($content));
        if(strlen($plain) < 1) {
            return back()
                ->withErrors(['content' => 'Must have at least one character.']);
        }
        if(strlen($plain) > 5000) {
            return back()
                ->withErrors(['content' => 'Must have less than 5000 characters.']);
        }



        $validated = $request->validate([
            'title' => ['required', 'string', 'min:4', 'max:100'],
            'content' => ['required', 'string']
        ]);

    try {
        DB::beginTransaction();
        $validated['content'] = trim($validated['content']);


        $thread = $forum->threads()->create([
            'title' => $validated['title'],
            'user_id' => Auth::user()->id,
        ]);

        $thread->posts()->create([
            'user_id' => Auth::user()->id,
            'content' => $validated['content']
        ]);

        DB::commit();

        return redirect()->route('threads.show', [$thread, $thread->slug])
        ->with('success', 'Thread created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Thread creation failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['title' => 'Failed to create thread. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(Thread $thread) {
        Gate::authorize('delete', $thread);
        $thread->delete($thread->id);
        return back();
    }
}
