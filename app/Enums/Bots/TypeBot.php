<?php

declare(strict_types=1);

namespace App\Enums\Bots;

enum TypeBot: string
{
    case INFO_BOT = 'info_bot';
    case ERROR_BOT = 'error_bot';
    case TRANSACTIONS_BOT = 'transactions_bot';
    case ADMIN_CHANNEL_BOT = 'admin_channel_bot';
}
