<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeExercise: string
{
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';
    case THEORETICAL = 'theoretical';
    case PRINT_TEXT = 'print_text';

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Одиночный выбор',
            self::MULTIPLE => 'Множественный выбор',
            self::THEORETICAL => 'Теоритическое',
            self::PRINT_TEXT => 'Ввод текста',
        };
    }
}
