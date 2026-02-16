<?php

declare(strict_types=1);

namespace App\Repositories\TransactionRepository;

use App\DTO\Transaction\CreateTransactionDTO;
use App\Models\Transaction;

interface TransactionRepositoryContract
{
    public function sumAmountByUser(int $userId): float;

    public function create(CreateTransactionDTO $data): Transaction;
}
