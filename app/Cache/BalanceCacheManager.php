<?php

declare(strict_types=1);

namespace App\Cache;

use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use Illuminate\Support\Facades\Cache;

final class BalanceCacheManager
{
    const TTL = 300;

    public function __construct(private readonly TransactionRepositoryContract $transactionRepository) {}

    public function get(int $user_id): float
    {
        return (float) Cache::remember(
            $this->getCacheKeyForBalanceUser($user_id),
            self::TTL,
            fn () => $this->transactionRepository->calculateBalanceUser($user_id),
        );
    }

    public function forget(int $user_id): void
    {
        Cache::forget($this->getCacheKeyForBalanceUser($user_id));
    }

    private function getCacheKeyForBalanceUser(int $user_id): string
    {
        return "user_balance_{$user_id}";
    }
}
