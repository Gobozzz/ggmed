<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\Enums\DTO\Transaction\AdminReplenishedPayDTO;
use App\Enums\DTO\Transaction\AdminWriteOffDTO;

interface TransactionServiceContract
{
    public function adminReplenished(AdminReplenishedPayDTO $data): void;

    public function writeOffAdmin(AdminWriteOffDTO $data): void;
}
