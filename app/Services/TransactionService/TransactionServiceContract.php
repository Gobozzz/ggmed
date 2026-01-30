<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\DTO\Transaction\AdminReplenishedPayDTO;
use App\DTO\Transaction\AdminWriteOffDTO;

interface TransactionServiceContract
{
    public function getBalanceUser(int $user_id): float;

    public function payAdminReplenished(AdminReplenishedPayDTO $data): void;

    public function writeOffAdmin(AdminWriteOffDTO $data): void;
}
