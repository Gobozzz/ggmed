<?php

declare(strict_types=1);

namespace App\Enums\Exceptions;

/**
 * Range 300-399
 */
enum AiAssistantException: int
{
    case INVALID_RESPONSE = 300;
    case AUTHENTICATION_ERROR = 301;
    case INVALID_RESPONSE_DATA = 302;
}
