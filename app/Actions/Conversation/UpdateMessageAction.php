<?php
namespace App\Actions\Conversation;

use App\Models\Message;

class UpdateMessageAction
{
    public function execute(Message $message, array $data): Message
    {
        $plain = trim(strip_tags($data['content']));

        [$min, $max] = match ($data['content']) {
            default => [1, 5000],
        };

        if (strlen($plain) < $min) {
            throw new \InvalidArgumentException("Minimum $min characters required.");
        }

        if (strlen($plain) > $max) {
            throw new \InvalidArgumentException("Maximum $max characters allowed.");
        }

        $message->update([
            'content' => trim($data['content']),
        ]);

        return $message;
    }
}
