<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;

class Thread extends Model
{
    /** @use HasFactory<\Database\Factories\ThreadFactory> */
    use HasFactory;
    use Searchable;

    #[SearchUsingFullText(['title'])]
    public function
    toSearchableArray(): array
    {
        return [
            'title' => $this->title,
        ];
    }

    public function forum() {
        return $this->belongsTo(Forum::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function latestPost()
    {
        return $this->hasOne(Post::class)->latestOfMany();
    }

    protected $fillable = [
        'title',
        'slug',
        'forum_id',
        'user_id',
    ];


    protected static function boot() {
        parent::boot();

        static::creating(function (Thread $thread) {
            $baseSlug = Str::slug($thread->title);
            $slug = $baseSlug;
            $i = 1;

            while(Thread::query()->where('forum_id', $thread->forum_id)
                ->where('slug', $slug)
                ->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }
                $thread->slug = $slug;
        });
    }

    public function getAuthorAttribute()
    {
        if ($this->user) {
            return $this->user;
        }

        return new User([
            'id' => $this->user_id,
            'name'          => 'Deleted Member ' . $this->user_id,
            'profile_image' => null,
            'role'          => 'former_member',
            'email'         => null,
        ]);
    }
}
