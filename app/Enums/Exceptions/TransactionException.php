<?php

declare(strict_types=1);

namespace App\Enums\Exceptions;

/**
 * Range 100-199
 */
enum TransactionException: int
{
    case INSUFFICIENT_FUNDS = 100;
    case AMOUNT_INCORRECT = 101;
}
