<?php

declare(strict_types=1);

namespace App\Exceptions\Transactions;

use App\Enums\Exceptions\TransactionException;
use App\Exceptions\BaseException;

final class InsufficientFundsException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            "There are insufficient funds on the user's balance",
            TransactionException::INSUFFICIENT_FUNDS->value,
        );
    }
}
