<?php
namespace App\Actions;

use App\Models\Post;

class UpdatePostAction
{
    public function execute(Post $post, array $data, string $context): Post
    {
        $plain = trim(strip_tags($data['content']));

        [$min, $max] = match ($context) {
            'profile' => [1, 1000],
            'thread' => [1, 5000],
            default => [1, 1000],
        };

        if (strlen($plain) < $min) {
            throw new \InvalidArgumentException("Minimum $min characters required.");
        }

        if (strlen($plain) > $max) {
            throw new \InvalidArgumentException("Maximum $max characters allowed.");
        }

        $post->update([
            'content' => trim($data['content']),
        ]);

        return $post;
    }
}
