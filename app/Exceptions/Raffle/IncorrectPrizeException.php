<?php

namespace App\Exceptions\Raffle;

use App\Enums\Exceptions\RaffleException;
use App\Exceptions\BaseApiException;

class IncorrectPrizeException extends BaseApiException
{
    public function __construct()
    {
        parent::__construct(
            "Incorrect prize for raffle",
            RaffleException::INCORRECT_PRIZE->value,
        );
    }
}
