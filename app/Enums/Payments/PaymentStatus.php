<?php

declare(strict_types=1);

namespace App\Enums\Payments;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Ожидаем оплаты',
            self::PAID => 'Оплачен',
            self::REFUNDED => 'Возврат',
            self::CANCELLED => 'Отменен',
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
