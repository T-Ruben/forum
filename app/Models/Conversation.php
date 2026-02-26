<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    protected static function boot() {
        parent::boot();

        static::creating(function (Conversation $conversation) {
            $baseSlug = Str::slug($conversation->title);
            $slug = $baseSlug;
            $i = 1;

            while(Conversation::where('slug', $slug)
                ->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }
                $conversation->slug = $slug;
        });
    }
}
