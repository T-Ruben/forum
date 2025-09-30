<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Forum extends Model
{
    /** @use HasFactory<\Database\Factories\ForumFactory> */
    use HasFactory;

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function forumCategory() {
        return $this->belongsTo(ForumCategory::class);
    }

    public function posts() {
        return $this->hasManyThrough(Post::class, Thread::class);
    }

    public function latestThread() {
        return $this->hasOne(Thread::class)->latestOfMany();
    }

    protected $fillable = [
        'title',
        'slug',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($forum) {
            $forum->slug = Str::slug($forum->title);
        });
    }


}
