<?php

namespace App\Services;

use App\Notifications\ConversationMessageNotification;
use Auth;

class MessageService
{
    /**
     * Create a new class instance.
     */
    public function store(array $validated)
    {

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
    }

    public function update(array $validated, object $message) {
        $page = $message->getPageNumber();
        $conversation = $message->conversation;

        $message->update(['content' => trim($validated['content'])]);

        return ['conversation' => $conversation, 'page' => $page];
    }

    public function destroy(object $message) {
        $page = $message->getPageNumber();
        $conversation = $message->conversation;

        $message->update([
            'content' => '[deleted]',
        ]);

        $message->delete($message->id);

        return ['conversation' => $conversation, 'page' => $page];
    }
}
