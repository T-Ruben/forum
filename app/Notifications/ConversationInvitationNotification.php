<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConversationInvitationNotification extends Notification
{
    use Queueable;

    protected $conversation;
    protected $inviter;
    protected $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
        $this->conversation = $invitation->conversation;
        $this->inviter = $invitation->inviter;
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
    public function toArray(object $notifiable): array
    {
        return [
            'invitation_id' => $this->invitation->id,
            'conversation_id' => $this->conversation->id,
            'inviter_id' => $this->inviter->id,
        ];
    }
}
