<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ForumCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ForumCategoryFactory> */
    use HasFactory;

    public function forums() {
        return $this->hasMany(Forum::class);
    }

    protected $fillable = [
        'title',
    ];

}

