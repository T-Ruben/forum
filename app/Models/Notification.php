<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;
use Illuminate\Database\Eloquent\MassPrunable;


class Notification extends BaseNotification
{
    use MassPrunable;

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function prunable()
    {
        return static::whereNotNull('read_at')
            ->where('read_at', '<=', now()->subDays(30));
    }


}
