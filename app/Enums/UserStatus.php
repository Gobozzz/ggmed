<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVED = 'actived';
    case BLOCKED = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVED => 'Активен',
            self::BLOCKED => 'Заблокирован',
        };
    }
}
