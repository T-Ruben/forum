<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
{
    public function show(Thread $thread)
    {
        $thread->load(['latestPost.user',]);


        $posts = $thread->posts()
            ->with(['user' => function ($query) {
                $query->withCount('posts');
            }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('threads.show', [
            'thread' => $thread,
            'posts' => $posts,
        ]);
    }

    public function create(Forum $forum) {
        return view('threads.create', ['forum' => $forum]);
    }

    public function store(Request $request, Forum $forum){

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:4', 'max:100'],
            'content' => ['required', 'string', 'min:1', 'max:4000']
        ]);

    try {
        DB::beginTransaction();

        $thread = $forum->threads()->create([
            'title' => $validated['title'],
            'user_id' => Auth::user()->id,
        ]);

        $thread->posts()->create([
            'user_id' => Auth::user()->id,
            'content' => $validated['content']
        ]);

        DB::commit();

        return redirect()
        ->route('threads.show', [$thread->id, $thread->slug])
        ->with('success', 'Thread created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Thread creation failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['title' => 'Failed to create thread. Please try again.'])
                ->withInput();
        }


    }
}
