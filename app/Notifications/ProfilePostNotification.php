<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Override;

class ProfilePostNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $post;
    protected $sender;
    /**
     * Create a new notification instance.
     */
    public function __construct($post, $type = 'new_post')
    {
        $this->type = $type;
        $this->post = $post;
        $this->sender = $post->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
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
        return new BroadcastMessage([
            'sender' => $this->sender->id,
            'message' => $this->sender->display_name . ' ' . ($this->type === 'new_post' ? 'sent a new post on your profile.'
                : 'replied to a post on your profile.'),
        ]);
    }

    #[Override]
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->post->profile_user_id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $profileId = (int) $this->post->profile_user_id;
        $notifiableId = (int) $notifiable->id;

        $action = 'sent a post on';
        $location = 'your profile';

        if ($this->type === 'reply') {
            if ($profileId === $notifiableId) {
                $action = 'replied to a post on';
                $location = 'your profile';
            }
            else {
                $action = 'replied to your post on';
                $location = $this->post->profileOwner->display_name . "'s profile";
            }
        }

        return [
            'type' => $this->type,

            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->display_name,
                'avatar' => $this->sender->profile_image_url,
                'content' => Str::limit($this->post->content, 50, '...')
            ],

            'profile_name' => $this->post->profileOwner->name,
            'profile_id' => $this->post->profile_user_id,
            'target_url' => route('notification.jump', $this->id),

            'post_id' => $this->post->id,
            'action' => $action,
            'location' => $location,

        ];
    }
}
