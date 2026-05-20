<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Override;

class FollowingNotification extends Notification
{
    use Queueable;

    public $follower;
    public $followed;
    protected $type;


    /**
     * Create a new notification instance.
     */
    public function __construct($follower, $followed, $type = 'following')
    {
        $this->follower = $follower;
        $this->type = $type;
        $this->followed = $followed;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $prefs = $notifiable->muted_notifications ?? [];

        if($prefs['all'] ?? false) {
            return [];
        }

        $mutedTypes = $prefs['types'] ?? [];
        if(in_array('follow', $mutedTypes)) {
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
        return new BroadcastMessage([
            'userId' => $this->follower->id,
            'message' => $this->follower->display_name . ' is now following you.'
        ]);
    }

    #[Override]
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->followed->id);
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
                'id' => $this->follower->id,
                'name' => $this->follower->display_name,
                'avatar' => $this->follower->profile_image_url,
            ],
        ];
    }
}
