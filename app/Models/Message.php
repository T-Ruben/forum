<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use SoftDeletes;

    protected $touches = ['conversation'];

    protected $fillable = [
        'content',
        'parent_id',
        'conversation_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id')
            ->withTrashed();
    }

    public function getPageNumber($perPage = 10)
    {
        $count = self::where('conversation_id', $this->conversation_id)
            ->where('created_at', '<', $this->created_at)
            ->count();
        return (int) ceil(($count + 1) / $perPage);

    }

}
