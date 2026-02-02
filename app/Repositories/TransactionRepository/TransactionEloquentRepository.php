<?php

declare(strict_types=1);

namespace App\Repositories\TransactionRepository;

use App\Enums\DTO\Transaction\CreateTransactionDTO;
use App\Models\Transaction;

final class TransactionEloquentRepository implements TransactionRepositoryContract
{
    public function calculateBalanceUser(int $user_id): float
    {
        return (float) Transaction::query()->where('user_id', $user_id)->sum('amount');
    }

    public function create(CreateTransactionDTO $data): Transaction
    {
        return Transaction::query()->create([
            'user_id' => $data->user_id,
            'type' => $data->type,
            'amount' => $data->amount,
            'description' => $data->description,
            'metadata' => $data->metadata,
        ]);
    }
}
