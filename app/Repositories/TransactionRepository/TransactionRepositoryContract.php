<?php

declare(strict_types=1);

namespace App\Repositories\TransactionRepository;

use App\Enums\DTO\Transaction\CreateTransactionDTO;
use App\Models\Transaction;

interface TransactionRepositoryContract
{
    public function calculateBalanceUser(int $user_id): float;

    public function create(CreateTransactionDTO $data): Transaction;
}
