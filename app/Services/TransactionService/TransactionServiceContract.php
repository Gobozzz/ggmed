<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\DTO\Transaction\AdminReplenishedDTO;
use App\DTO\Transaction\AdminWriteOffDTO;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Models\Transaction;

interface TransactionServiceContract
{
    public function adminReplenished(AdminReplenishedDTO $data): void;

    public function writeOffAdmin(AdminWriteOffDTO $data): void;

    public function winningWeeklyRaffle(WinningWeeklyRaffleDTO $data): Transaction;
}
