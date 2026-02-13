<?php

declare(strict_types=1);

namespace App\Enums;

enum RaffleType: string
{
    case MANUAL = 'manual';
    case WEEKLY = 'weekly';

    public function label(): string
    {
        return match ($this) {
            self::MANUAL => 'Ручной',
            self::WEEKLY => 'Еженедельный',
        };
    }

    public static function getAll(): array
    {
        $levels = [];

        foreach (self::cases() as $level) {
            $levels[$level->value] = $level->label();
        }

        return $levels;
    }
}
