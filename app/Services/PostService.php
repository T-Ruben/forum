<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Notifications\ProfilePostNotification;
use App\Notifications\ThreadPostNotification;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * Create a new class instance.
     */
    public function store($validated): void
    {
        $post = Auth::user()->posts()->create($validated);

        $userOwner = $post->thread->user;
        $userReply = $post->parent?->user;

        if($post->parent) {
            $userReply->notify(new ThreadPostNotification($post, $type = 'reply'));

            if($post->user_id !== $post->thread->user_id && $post->parent->user_id !== $post->thread->user_id) {
                $userOwner->notify(new ThreadPostNotification($post));
            }
        } elseif($post->user_id !== $post->thread->user_id) {
            $userOwner->notify(new ThreadPostNotification($post));
        }
    }
}
