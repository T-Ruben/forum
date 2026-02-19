<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'content',
        'thread_id',
        'user_id',
        'parent_id',
        'profile_user_id',
        'deleted_at'
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
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
        return $this->hasMany(Post::class, 'parent_id')
            ->withTrashed();
    }

    public function recursiveReplies() {
        return $this->replies()->with(['user', 'recursiveReplies']);
    }


    public function getPageNumber($perPage = 10)
        {
            $count = self::where('thread_id', $this->thread_id)
                ->where('created_at', '<', $this->created_at)
                ->count();
            return (int) ceil(($count + 1) / $perPage);

            }
    public function getPageNumberProfile($perPage = 10)
        {
            $target = $this->parent_id
                ? self::find($this->parent_id)
                : $this;

            $count = self::whereNull('parent_id')
                ->where('profile_user_id', $target->profile_user_id)
                ->where(function ($query) use ($target){
                    $query->where('created_at', '>', $target->created_at)
                    ->orWhere(function ($query) use ($target) {
                        $query->where('created_at', $target->created_at)
                            ->where('id', '>', $target->id);
                    });
                })
                ->count();

            return (int) ceil(($count + 1) / $perPage);
        }


}
