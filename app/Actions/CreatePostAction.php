<?php
namespace App\Actions;
use App\Models\Post;
use App\Models\User;
use App\Notifications\ProfilePostNotification;


class CreatePostAction
{
public function execute(User $user, array $data): Post
{
    $replyToPost = null;

    if (!empty($data['parent_id'])) {
        $replyToPost = Post::with('user')->findOrFail($data['parent_id']);

        $data['parent_id'] = $replyToPost->parent_id ?? $replyToPost->id;
    }

    $post = $user->posts()->create($data);

    if ($post->parent && $replyToPost && $post->user_id !== $replyToPost->user_id) {
        $replyToPost->user->notify(
            new ProfilePostNotification($post, 'reply')
        );
    }
    elseif ($user->id !== $post->user_id && !$post->parent_id) {
        $user->notify(new ProfilePostNotification($post));
    }

    return $post;
}
}

