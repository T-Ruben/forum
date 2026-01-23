<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'content',
        'thread_id',
        'user_id',
        'parent_id',
        'profile_user_id'
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed()->withDefault([
                'name' => 'Deleted Member'
            ]);
    }

    public function profileOwner()
    {
        return $this->belongsTo(User::class, 'profile_user_id')
            ->withTrashed();
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function author() {
        return $this->belongsTo(User::class)
            ->withTrashed();
    }

    public function getPageNumber($perPage = 10)
        {
            $count = self::where('thread_id', $this->thread_id)
                ->where('created_at', '<', $this->created_at)
                ->count();
            return (int) ceil(($count + 1) / $perPage);
        }

    public function getAuthorAttribute()
    {
        if ($this->user) {
            return $this->user;
        }

        return new User([
            'name'          => 'Deleted Member',
            'profile_image' => null,
            'role'          => 'Former Member',
            'email'         => null,
            'created_at'    => now(),
        ]);
    }



}
