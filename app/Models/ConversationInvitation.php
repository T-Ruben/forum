<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationInvitation extends Model
{
    protected $fillable = [
        'conversation_id',
        'inviter_id',
        'invited_user_id',
        'status'
    ];

    public function conversation() {
        return $this->belongsTo(Conversation::class);
    }

    public function inviter() {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitedUser() {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

}
