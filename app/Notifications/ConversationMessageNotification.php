<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConversationMessageNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $type = 'new_message')
    {
        $this->type = $type;
        $this->message = $message;
        $this->sender = $message->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if($this->message->user_id === $notifiable->id) {
            return [];
        }
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
                'id' => $this->sender->id,
                'name' => $this->sender->display_name,
                'avatar' => $this->sender->profile_image_url
            ],

            'conversation' => [
                'id' => $this->message->conversation->id,
                'title' => $this->message->conversation->title,
                'members_count' => $this->message->conversation->users()
                    ->distinct('user_id')
                    ->count('user_id')
            ],

            'message_id' => $this->message->id
        ];
    }
}
