<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Override;

class ThreadPostNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $type;
    protected $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($post, $type = 'new_post')
    {
        $this->post = $post;
        $this->type = $type;
        $this->sender = $post->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if($this->post->user_id === $notifiable->id) {
            return [];
        }

        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $preparedPost = null;
        if($this->type === 'new_post') {
            $preparedPost = $this->sender->display_name . ' sent a new post in Thread: ' . $this->post->thread->title;
        } else {
            $preparedPost = $this->sender->display_name . ' replied to your post in Thread: ' . $this->post->thread->title;
        }

        return new BroadcastMessage([
            'thread_id' => $this->post->thread,
            'post_id' => $this->post->id,
            'message' => $preparedPost,
        ]);
    }

    #[Override]
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->post->thread->user_id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,

            'thread' => [
                'id' => $this->post->thread_id,
                'title' => $this->post->thread->title,
                'slug' => $this->post->thread->slug,
                'members_count' => $this->post->thread->posts()
                    ->distinct('user_id')
                    ->count('user_id')
            ],

            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->display_name,
                'avatar' => $this->sender->profile_image_url
            ],

            'target_url' => route('notification.jump', $this->id),

            'post_id' => $this->post->id
        ];
    }
}
