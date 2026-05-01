<?php

namespace App\Http\Controllers;

use App\Http\Requests\Thread\ThreadStoreRequest;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Services\ThreadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;

class ThreadController extends Controller
{
    public function show(Thread $thread, Request $request, ThreadService $service)
    {
        $filter = [
            'reply' => $request->reply_to,
            'edit' => $request->edit_post
        ];

        $data = $service->threadService($filter, $thread);

        return view('threads.show', $data);
    }

    public function create(Forum $forum) {
        return view('threads.create', ['forum' => $forum]);
    }

    public function store(ThreadStoreRequest $request, Forum $forum, ThreadService $service){
        Gate::authorize('create', [Thread::class, $forum]);

        $validated = $request->validated();

    try {
        DB::beginTransaction();

        $data = $service->createThread($forum, $validated);

        DB::commit();

        return redirect()->route('threads.show', $data)
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
