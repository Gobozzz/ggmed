<?php

declare(strict_types=1);

namespace App\Enums;
enum ChannelLog: string
{
    case FILE = "single";

    case ERROR = "telegram_errors";
    case INFO = "telegram_info";
}
