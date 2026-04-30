<?php

namespace App\Http\Controllers;

use App\Http\Requests\Conversation\ConversationStoreRequest;
use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
use App\Services\ConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Conversation $conversation, User $user)
    {
        return view('conversations.create', ['conversation' => $conversation, 'user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConversationStoreRequest $request, User $user, ConversationService $service)
    {
        Gate::authorize('create', Conversation::class);

        $validated = $request->validated();

        try {
            $conversation = $service->store($validated, Auth::user(), $user);

        return redirect()->route('conversation.show', $conversation->id);
        } catch (\Exception $e) {
            Log::error('Conversation creation failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['title' => 'Failed to create thread. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation, Request $request)
    {
        Gate::authorize('view', $conversation);
        $conversation = $conversation->load(['messages']);

        $replyTo = null;
        $editMessage = null;

        if($request->filled('edit_message')) {
            $editMessage = Message::where('conversation_id', $conversation->id)
                ->findOrFail($request->edit_message);

            Gate::authorize('update', $editMessage);
        }
            elseif($request->filled('reply_to'))
        {
            $replyTo = Message::where('conversation_id', $conversation->id)
                ->findOrFail($request->reply_to);
        }

        $messages = $conversation->messages()
            ->with(['user.following', 'parent.user', 'user.followers', 'user' => function ($query) {
                $query->withCount('messages', 'followers', 'following');
            }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('conversations.show', [$conversation->id,
                                            'conversation' => $conversation,
                                            'messages' => $messages,
                                            'replyTo' => $replyTo,
                                            'editMessage' => $editMessage]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    public function leave(Conversation $conversation)
    {
        Gate::authorize('leave', $conversation);
        $conversation->users()->detach(Auth::id());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
