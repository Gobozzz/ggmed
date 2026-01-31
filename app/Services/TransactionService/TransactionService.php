<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\Cache\BalanceCacheManager;
use App\DTO\Transaction\AdminReplenishedPayDTO;
use App\DTO\Transaction\AdminWriteOffDTO;
use App\DTO\Transaction\CreateTransactionDTO;
use App\Enums\TypeTransaction;
use App\Exceptions\Transactions\AmountIncorrectException;
use App\Exceptions\Transactions\InsufficientFundsException;
use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use Illuminate\Support\Facades\DB;

final class TransactionService implements TransactionServiceContract
{
    public function __construct(
        private readonly TransactionRepositoryContract $transactionRepository,
        private readonly UserRepositoryContract        $userRepository,
        private readonly BalanceCacheManager           $balanceCacheManager,
    )
    {
    }

    public function payAdminReplenished(AdminReplenishedPayDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            $this->checkCorrectAmount($data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_REPLENISHED,
                amount: $data->amount,
                user_id: $data->user_id,
                description: $data->description,
                metadata: ['admin_id' => $data->admin_id],
            );

            $this->transactionRepository->create($createDTO);

            DB::afterCommit(function () use ($data) {
                $this->balanceCacheManager->forget($data->user_id);
            });

        }, config('transactions.count_attempts_transaction'));
    }

    public function writeOffAdmin(AdminWriteOffDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            $this->checkCorrectAmount($data->amount);

            $balance_user = $this->transactionRepository->calculateBalanceUser($data->user_id);

            $this->checkBalanceForWriteOff($balance_user, $data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_WRITE_OFF,
                amount: -$data->amount,
                user_id: $data->user_id,
                description: $data->description,
                metadata: ['admin_id' => $data->admin_id],
            );

            $this->transactionRepository->create($createDTO);

            DB::afterCommit(function () use ($data) {
                $this->balanceCacheManager->forget($data->user_id);
            });

        }, config('transactions.count_attempts_transaction'));
    }

    private function checkCorrectAmount(float $amount): void
    {
        if ($amount < config('transactions.min_amount_transaction') || $amount > config('transactions.max_amount_transaction')) {
            throw new AmountIncorrectException();
        }
    }

    private function checkBalanceForWriteOff(float $balance, float $amount): void
    {
        if ($balance < $amount) {
            throw new InsufficientFundsException();
        }
    }
}
