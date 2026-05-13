<?php

namespace App\Actions\Conversation;

use App\Models\Message;

class DeleteMessageAction
{
    public function execute(Message $message): void
    {
        $message->update([
            'content' => '[deleted]',
        ]);

        $message->delete($message->id);
    }
}
