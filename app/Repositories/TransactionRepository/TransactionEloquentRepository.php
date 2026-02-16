<?php

declare(strict_types=1);

namespace App\Repositories\TransactionRepository;

use App\DTO\Transaction\CreateTransactionDTO;
use App\Models\Transaction;

final class TransactionEloquentRepository implements TransactionRepositoryContract
{
    public function sumAmountByUser(int $userId): float
    {
        return (float) Transaction::query()->where('user_id', $userId)->sum('amount');
    }

    public function create(CreateTransactionDTO $data): Transaction
    {
        return Transaction::query()->create([
            'user_id' => $data->userId,
            'type' => $data->type,
            'amount' => $data->amount,
            'description' => $data->description,
            'metadata' => $data->metadata,
        ]);
    }
}
