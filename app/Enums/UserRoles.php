<?php

namespace App\Enums;

enum UserRoles: string
{
    case Member = 'member';
    case FormerMember = 'former_member';

    public function label(): string
    {
        return match($this) {
            self::Member => 'Member',
            self::FormerMember => 'Former Member',
        };
    }
}
