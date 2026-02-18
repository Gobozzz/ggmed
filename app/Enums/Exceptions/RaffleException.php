<?php

declare(strict_types=1);

namespace App\Enums\Exceptions;

/**
 * Range 200-299
 */
enum RaffleException: int
{
    case NOT_FOUND_READY_WEEKLY = 200;
    case NO_SET_WINNER = 201;
}
