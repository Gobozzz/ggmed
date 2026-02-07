<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelHipe: int
{
    case LOW = 1;
    case MIDDLE = 2;
    case HIGH = 3;

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Низкий',
            self::MIDDLE => 'Средний',
            self::HIGH => 'Высокий',
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
