<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\Cache\BalanceCacheManager;
use App\DTO\Transaction\AdminReplenishedDTO;
use App\DTO\Transaction\AdminWriteOffDTO;
use App\DTO\Transaction\CreateTransactionDTO;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Enums\ChannelLog;
use App\Enums\TypeTransaction;
use App\Exceptions\Transactions\AmountIncorrectException;
use App\Exceptions\Transactions\InsufficientFundsException;
use App\Models\Transaction;
use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TransactionService implements TransactionServiceContract
{
    public function __construct(
        private readonly TransactionRepositoryContract $transactionRepository,
        private readonly UserRepositoryContract $userRepository,
        private readonly BalanceCacheManager $balanceCacheManager,
    ) {}

    public function adminReplenished(AdminReplenishedDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            DB::afterRollBack(function () use ($data) {
                $this->transactionLog('Транзакция упала[!]', TypeTransaction::ADMIN_REPLENISHED, $data);
            });

            $this->checkCorrectAmount($data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_REPLENISHED,
                amount: abs($data->amount),
                user_id: $data->user_id,
                description: $data->description,
                metadata: ['admin_id' => $data->admin_id],
            );

            $this->transactionRepository->create($createDTO);

            DB::afterCommit(function () use ($data) {
                $this->balanceCacheManager->forget($data->user_id);
                $this->transactionLog('Транзакция выполнилась успешно', TypeTransaction::ADMIN_REPLENISHED, $data);
            });

        }, config('transactions.count_attempts_transaction'));
    }

    public function writeOffAdmin(AdminWriteOffDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            DB::afterRollBack(function () use ($data) {
                $this->transactionLog('Транзакция упала[!]', TypeTransaction::ADMIN_WRITE_OFF, $data);
            });

            $this->checkCorrectAmount($data->amount);

            $balance_user = $this->transactionRepository->calculateBalanceUser($data->user_id);

            $this->checkBalanceForWriteOff($balance_user, $data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_WRITE_OFF,
                amount: -abs($data->amount),
                user_id: $data->user_id,
                description: $data->description,
                metadata: ['admin_id' => $data->admin_id],
            );

            $transaction = $this->transactionRepository->create($createDTO);

            DB::afterCommit(function () use ($data, $transaction) {
                $this->balanceCacheManager->forget($data->user_id);
                $this->transactionLog("Транзакция №{$transaction->getKey()} выполнилась успешно", TypeTransaction::ADMIN_WRITE_OFF, $data);
            });

        }, config('transactions.count_attempts_transaction'));
    }

    public function winningWeeklyRaffle(WinningWeeklyRaffleDTO $data): Transaction
    {
        $this->checkCorrectAmount($data->amount);

        $createDTO = new CreateTransactionDTO(
            type: TypeTransaction::WINNING_RAFFLE,
            amount: abs($data->amount),
            user_id: $data->user_id,
            description: $data->description,
            metadata: ['raffle_id' => $data->raffle_id],
        );

        return $this->transactionRepository->create($createDTO);
    }

    private function transactionLog(string $message, TypeTransaction $type, mixed $data): void
    {
        try {
            $data = json_encode($data);
        } catch (\Exception $e) {
            $data = 'Не удалось сериализовать данные транзакции';
        }
        Log::channel(ChannelLog::TRANSACTIONS->value)->info("{$message}\nТип:{$type->label()}\n\n".$data);
    }

    private function checkCorrectAmount(float $amount): void
    {
        if ($amount < config('transactions.min_amount_transaction') || $amount > config('transactions.max_amount_transaction')) {
            throw new AmountIncorrectException;
        }
    }

    private function checkBalanceForWriteOff(float $balance, float $amount): void
    {
        if ($balance < $amount) {
            throw new InsufficientFundsException;
        }
    }
}
