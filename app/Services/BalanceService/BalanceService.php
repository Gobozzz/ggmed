<?php

declare(strict_types=1);

namespace App\Services\BalanceService;

use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use Illuminate\Support\Facades\Cache;

final class BalanceService implements BalanceServiceContract
{
    public function __construct(private readonly TransactionRepositoryContract $transactionRepository) {}

    public function getUserBalance(int $userId): float
    {
        return $this->transactionRepository->sumAmountByUser($userId);
    }

    public function getUserBalanceCached(int $userId): float
    {
        return (float) Cache::remember(
            $this->getCacheKey($userId),
            config('cache.balance_ttl', 900),
            fn () => $this->getUserBalance($userId)
        );
    }

    public function hasSufficientBalance(int $userId, float $requiredAmount): bool
    {
        return $this->getUserBalance($userId) >= $requiredAmount;
    }

    public function invalidateBalanceCache(int $userId): void
    {
        Cache::forget($this->getCacheKey($userId));
    }

    private function getCacheKey(int $userId): string
    {
        return "user_balance_{$userId}";
    }
}
