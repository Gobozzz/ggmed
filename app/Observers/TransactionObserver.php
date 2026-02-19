<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Transaction;
use App\Services\BalanceService\BalanceServiceContract;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final class TransactionObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        private readonly BalanceServiceContract $balanceService,
    ) {}

    public function created(Transaction $transaction): void
    {
        $this->balanceService->invalidateBalanceCache($transaction->user_id);
    }

    public function updated(Transaction $transaction): void
    {
        $this->balanceService->invalidateBalanceCache($transaction->user_id);
    }

    public function deleted(Transaction $transaction): void
    {
        $this->balanceService->invalidateBalanceCache($transaction->user_id);
    }
}
