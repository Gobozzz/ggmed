<?php

declare(strict_types=1);

namespace App\Services\BalanceService;

interface BalanceServiceContract
{
    public function getUserBalance(int $userId): float;

    public function getUserBalanceCached(int $userId): float;

    public function hasSufficientBalance(int $userId, float $requiredAmount): bool;

    public function invalidateBalanceCache(int $userId): void;

}
