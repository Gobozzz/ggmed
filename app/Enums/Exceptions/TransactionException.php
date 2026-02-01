<?php

declare(strict_types=1);

namespace App\Enums\Exceptions;

/**
 * Range 1000-1999
 */
enum TransactionException: int
{
    case INSUFFICIENT_FUNDS = 1000;
    case AMOUNT_INCORRECT = 1001;
}
