<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\DTO\Transaction\AdminReplenishedDTO;
use App\DTO\Transaction\AdminWriteOffDTO;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Exceptions\Transactions\AmountIncorrectException;
use App\Exceptions\Transactions\InsufficientFundsException;
use App\Models\Transaction;

interface TransactionServiceContract
{
    /**
     * @throws AmountIncorrectException|\Throwable
     */
    public function adminReplenished(AdminReplenishedDTO $data): void;

    /**
     * @throws AmountIncorrectException|InsufficientFundsException|\Throwable
     */
    public function writeOffAdmin(AdminWriteOffDTO $data): void;

    /**
     * @throws AmountIncorrectException|\Throwable
     */
    public function winningWeeklyRaffle(WinningWeeklyRaffleDTO $data): Transaction;
}
