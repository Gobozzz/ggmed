<?php

declare(strict_types=1);

namespace App\Enums\Payments;

enum PaymentProvider: string
{
    case CASH = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Наличные',
        };
    }
}
