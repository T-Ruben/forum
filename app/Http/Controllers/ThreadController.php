<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;

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
        $validated['content'] = Purifier::clean(trim($validated['content']), 'quill');


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
