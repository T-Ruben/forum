<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\ConversationMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Conversation::class);

        $content = $request->input('content');
        $plain = trim(strip_tags($content));

    if(strlen($plain) < 1) {
        return back()->withErrors(['plain_content' => 'Must have at least one character.']);
    }

    if(strlen($plain) > 5000) {
        return back()
        ->withInput()
        ->withErrors(['plain_content' => 'Must have less than 5000 characters.']);
    }

    $validated = $request->validate([
        'content' => 'required|string',
        'conversation_id' => 'required|exists:conversations,id',
        'parent_id' => 'nullable|exists:messages,id'
    ]);

    try {
        $validated['content'] = trim($validated['content']);

        $message = Auth::user()->messages()->create($validated);

        $receiver = $message->parent?->user;
        $conversation = $message->conversation;

        $recipients = $conversation->users
            ->where('id', '!=', Auth::id());

        if ($receiver) {
            $receiver->notify(new ConversationMessageNotification($message, 'reply'));

            $recipients = $recipients->where('id', '!=', $receiver->id);
        }

        foreach ($recipients as $user) {
            $user->notify(new ConversationMessageNotification($message));
        }

        return back();
    } catch (\Exception $e) {
        Log::error('Message creation failed:',
                    ['user_id' => Auth::user()->id,
                     'error' => $e->getMessage()]);
        return back()
            ->withErrors(['content' => 'Something went wrong.'])
            ->withInput();
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        Gate::authorize('update', $message);

        $content = $request->input('content');
        $plain = trim(strip_tags($content));
        $page = $message->getPageNumber();
        $conversation = $message->conversation;

    if(strlen($plain) < 1) {
        return back()->withErrors(['content' => 'Must have at least one character.']);
    }

    if(strlen($plain) > 5000) {
        return back()
        ->withInput()
        ->withErrors(['content' => 'Must have less than 5000 characters.']);
    }

    $validated = $request->validate([
        'content' => 'required|string|accepted|max:5000'
    ]);

    try {
        $message->update(['content' => trim($validated['content'])]);
        return redirect()->route('conversation.show', ['conversation' => $conversation, 'page' => $page]);
    } catch (\Exception $e) {
        Log::error('Editing failed: ', ['error', $e->getMessage()]);
        return back()
            ->withErrors(['error' => 'Something went wrong while editing. Please try again.'])
            ->withInput();
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        Gate::authorize('delete', $message);

        $page = $message->getPageNumber();
        $conversation = $message->conversation;

        try {
            $message->delete();
            return redirect()->route('conversation.show', ['conversation' => $conversation, 'page' => $page]);
        } catch(\Exception $e) {
            Log::error('Something went wrong', ['error', $e->getMessage()]);
            return back();
        }

    }
}
