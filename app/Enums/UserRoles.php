<?php

namespace App\Enums;

enum UserRoles: string
{
    case Member = 'member';
    case FormerMember = 'former_member';
    case Admin = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Member => 'Member',
            self::FormerMember => 'Former Member',
        };
    }
}
