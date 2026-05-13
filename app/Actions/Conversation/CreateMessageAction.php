<?php
namespace App\Actions\Conversation;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Notifications\ConversationMessageNotification;


class CreateMessageAction
{
public function execute(User $user, array $data): Message
{
    $replyToMessage = null;

    if (!empty($data['parent_id'])) {
        $replyToMessage = Message::with('user')->findOrFail($data['parent_id']);

        $data['parent_id'] = $replyToMessage->parent_id ?? $replyToMessage->id;
    }

    $message = $user->messages()->create($data);

    if ($message->parent && $replyToMessage && $message->user_id !== $replyToMessage->user_id) {
        $replyToMessage->user->notify(
            new ConversationMessageNotification($message, 'reply')
        );
    }
    elseif (!$message->parent_id) {
        $conversation = $message->conversation;
        $users = $conversation->users;
        foreach($users as $userNotify) {
            $userNotify->notify(new ConversationMessageNotification($message));
        }

    }

    MessageSent::dispatch($message);

    return $message;
}
}

