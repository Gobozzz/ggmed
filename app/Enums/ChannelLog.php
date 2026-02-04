<?php

declare(strict_types=1);

namespace App\Enums;

enum ChannelLog: string
{
    case FILE = 'single';
    case ERRORS = 'errors';
    case INFO = 'info';
    case TRANSACTIONS = 'transactions';
}
