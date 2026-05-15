<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConversationInvitationNotification extends Notification
{
    use Queueable;

    public $conversation;
    protected $inviter;
    public $invitation;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($invitation, $type = 'conversation_invite')
    {
        $this->type = $type;
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
            'conversation_id' => $this->conversation->id,
            'invitation_id' => $this->invitation->id,
            'message' => 'New conversation invite in ' . $this->conversation->title
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->invitation->invited_user_id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,

            'invitation' => [
                'id' => $this->invitation->id,
            ],

            'conversation' => [
                'id' => $this->conversation->id,
                'title' => $this->conversation->title,
                'members_count' => $this->conversation->messages()
                    ->distinct('user_id')
                    ->count('user_id')
            ],

            'inviter' => [
                'id' => $this->inviter->id,
                'name' => $this->inviter->display_name,
                'avatar' => $this->inviter->profile_image_url
            ],
        ];
    }
}
