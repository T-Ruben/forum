<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    /** @use HasFactory<\Database\Factories\ThreadFactory> */
    use HasFactory;

    public function forum() {
        return $this->belongsTo(Forum::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    protected $fillable = [
        'title',
        'slug',
        'forum_id',
        'user_id',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($thread) {
            $thread->slug = Str::slug($thread->title);
        });
    }
}
