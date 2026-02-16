<?php

declare(strict_types=1);

namespace App\Enums\Exceptions;

/**
 * Range 200-299
 */
enum RaffleException: int
{
    case INCORRECT_PRIZE = 200;
    case NO_SET_WINNER = 201;
    case NOT_FOUND_READY_WEEKLY = 202;
}
