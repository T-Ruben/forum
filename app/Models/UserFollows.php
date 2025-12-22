<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollows extends Model
{
    /** @use HasFactory<\Database\Factories\UserFollowsFactory> */
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'followed_id',
    ];
}
