<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
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
    public function store(Request $request, User $user, ConversationInvitation $conversationInvitation)
    {
        Gate::authorize('create', Conversation::class);

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
            'title' => ['required', 'string', 'min:2', 'max:100'],
            'content' => ['required', 'string']
        ]);

        $validated['content'] = trim($validated['content']);

        try {
        $conversation = Conversation::create([
            'title' => $validated['title'],
        ]);

        // $conversation->users()->attach([$user->id, Auth::user()->id]);
        $conversation->users()->attach([Auth::user()->id]);

        $invitation = $conversationInvitation->create([
            'conversation_id' => $conversation->id,
            'inviter_id' => Auth::user()->id,
            'invited_user_id' => $user->id,
        ]);

        $conversation->messages()->create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::user()->id,
            'content' => $validated['content']
        ]);

        $user->notify(new ConversationInvitationNotification($invitation));

        return redirect()->route('conversation.show', $conversation->id);
        } catch (\Exception $e) {
            Log::error('Thread creation failed', ['error' => $e->getMessage()]);
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
                $query->withCount('messages');
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

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
