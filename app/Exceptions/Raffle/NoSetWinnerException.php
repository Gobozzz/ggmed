<?php

namespace App\Exceptions\Raffle;

use App\Enums\Exceptions\RaffleException;
use App\Exceptions\BaseApiException;

class NoSetWinnerException extends BaseApiException
{
    public function __construct()
    {
        parent::__construct(
            "No set winner for raffle",
            RaffleException::NO_SET_WINNER->value,
        );
    }
}
