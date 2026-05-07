<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowingNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $type;


    /**
     * Create a new notification instance.
     */
    public function __construct($user, $type = 'following')
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,

            'sender' => [
                'id' => $this->user->id,
                'name' => $this->user->display_name,
                'avatar' => $this->user->profile_image_url,
            ],

            'target_url' => route('notification.jump', $this->id),
        ];
    }
}
