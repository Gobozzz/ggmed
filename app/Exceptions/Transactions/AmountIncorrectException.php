<?php

declare(strict_types=1);

namespace App\Exceptions\Transactions;

use App\Enums\Exceptions\TransactionException;
use Exception;

class AmountIncorrectException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Amount incorrect',
            TransactionException::AMOUNT_INCORRECT->value,
        );
    }
}
