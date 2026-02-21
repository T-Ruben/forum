<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Conversation extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('deleted_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
