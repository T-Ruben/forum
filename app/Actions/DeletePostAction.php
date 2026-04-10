<?php

namespace App\Actions;

use App\Models\Post;

class DeletePostAction
{
    public function execute(Post $post): void
    {
        $post->update([
            'content' => '[deleted]',
        ]);

        $post->delete();
    }
}
