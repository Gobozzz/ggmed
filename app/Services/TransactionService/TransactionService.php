<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\DTO\Transaction\AdminReplenishedDTO;
use App\DTO\Transaction\AdminWriteOffDTO;
use App\DTO\Transaction\CreateTransactionDTO;
use App\DTO\Transaction\PayPrizeRaffleDTO;
use App\Enums\TypeTransaction;
use App\Exceptions\Transactions\AmountIncorrectException;
use App\Exceptions\Transactions\InsufficientFundsException;
use App\Models\Transaction;
use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\BalanceService\BalanceServiceContract;
use Illuminate\Support\Facades\DB;

final class TransactionService implements TransactionServiceContract
{
    public function __construct(
        private readonly TransactionRepositoryContract $transactionRepository,
        private readonly UserRepositoryContract        $userRepository,
        private readonly BalanceServiceContract        $balanceService,
    )
    {
    }

    public function adminReplenished(AdminReplenishedDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->getByIdAndLock($data->userId);

            $this->checkCorrectAmount($data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_REPLENISHED,
                amount: abs($data->amount),
                userId: $data->userId,
                description: $data->description,
                metadata: ['admin_id' => $data->adminId],
            );

            $this->transactionRepository->create($createDTO);
        }, config('transactions.count_attempts_transaction'));
    }

    public function writeOffAdmin(AdminWriteOffDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->getByIdAndLock($data->userId);

            $this->checkCorrectAmount($data->amount);

            $this->checkBalanceForWriteOff($data->userId, $data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_WRITE_OFF,
                amount: -abs($data->amount),
                userId: $data->userId,
                description: $data->description,
                metadata: ['admin_id' => $data->adminId],
            );

            $this->transactionRepository->create($createDTO);
        }, config('transactions.count_attempts_transaction'));
    }

    public function payPrizeRaffle(PayPrizeRaffleDTO $data): Transaction
    {
        $this->checkCorrectAmount($data->amount);

        $createDTO = new CreateTransactionDTO(
            type: TypeTransaction::WINNING_RAFFLE,
            amount: abs($data->amount),
            userId: $data->userId,
            description: $data->description,
            metadata: ['raffle_id' => $data->raffleId],
        );

        return $this->transactionRepository->create($createDTO);
    }

    private function checkCorrectAmount(float $amount): void
    {
        if ($amount < config('transactions.min_amount_transaction') || $amount > config('transactions.max_amount_transaction')) {
            throw new AmountIncorrectException;
        }
    }

    private function checkBalanceForWriteOff(int $userId, float $amount): void
    {
        if (!$this->balanceService->hasSufficientBalance($userId, $amount)) {
            throw new InsufficientFundsException;
        }
    }
}
