<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeTransaction: string
{
    // Пополнения
    case DAILY_BONUS = 'daily_bonus';
    case SALARY = 'salary';
    case ADMIN_REPLENISHED = 'admin_replenished';
    case REWARD = 'reward';

    // Списания
    case PURCHASE = 'purchase';
    case ADMIN_WRITE_OFF = 'admin_write_off';

    public function label(): string
    {
        return match ($this) {
            self::DAILY_BONUS => 'Ежедневный бонус',
            self::SALARY => 'Получение зарплаты',
            self::ADMIN_REPLENISHED => 'Пополнение от админа',
            self::REWARD => 'Награда',
            self::PURCHASE => 'Покупка',
            self::ADMIN_WRITE_OFF => 'Списание от админа',
        };
    }
}
