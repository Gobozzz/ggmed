<?php

declare(strict_types=1);

namespace App\Services\TransactionService;

use App\Cache\BalanceCacheManager;
use App\Enums\ChannelLog;
use App\Enums\DTO\Transaction\AdminReplenishedPayDTO;
use App\Enums\DTO\Transaction\AdminWriteOffDTO;
use App\Enums\DTO\Transaction\CreateTransactionDTO;
use App\Enums\TypeTransaction;
use App\Exceptions\Transactions\AmountIncorrectException;
use App\Exceptions\Transactions\InsufficientFundsException;
use App\Repositories\TransactionRepository\TransactionRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TransactionService implements TransactionServiceContract
{
    public function __construct(
        private readonly TransactionRepositoryContract $transactionRepository,
        private readonly UserRepositoryContract        $userRepository,
        private readonly BalanceCacheManager           $balanceCacheManager,
    )
    {
    }

    public function adminReplenished(AdminReplenishedPayDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            $this->transactionLog("Транзакция началась", TypeTransaction::ADMIN_REPLENISHED, $data);

            DB::afterCommit(function () use ($data) {
                $this->balanceCacheManager->forget($data->user_id);
                $this->transactionLog("Транзакция выполнилась успешно", TypeTransaction::ADMIN_REPLENISHED, $data);
            });

            DB::afterRollBack(function () use ($data) {
                $this->transactionLog("Транзакция упала[!]", TypeTransaction::ADMIN_REPLENISHED, $data);
            });

            $this->checkCorrectAmount($data->amount);

            $createDTO = new CreateTransactionDTO(
                type: TypeTransaction::ADMIN_REPLENISHED,
                amount: $data->amount,
                user_id: $data->user_id,
                description: $data->description,
                metadata: ['admin_id' => $data->admin_id],
            );

            $this->transactionRepository->create($createDTO);

        }, config('transactions.count_attempts_transaction'));
    }

    public function writeOffAdmin(AdminWriteOffDTO $data): void
    {
        DB::transaction(function () use ($data) {
            $this->userRepository->lockForUpdateById($data->user_id);

            $this->transactionLog("Транзакция началась", TypeTransaction::ADMIN_WRITE_OFF, $data);

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
                $this->transactionLog("Транзакция выполнилась успешно", TypeTransaction::ADMIN_REPLENISHED, $data);
            });

            DB::afterRollBack(function () use ($data) {
                $this->transactionLog("Транзакция упала[!]", TypeTransaction::ADMIN_REPLENISHED, $data);
            });

        }, config('transactions.count_attempts_transaction'));
    }

    private function transactionLog(string $message, TypeTransaction $type, mixed $data): void
    {
        try {
            $data = json_encode($data);
        } catch (\Exception $e) {
            $data = "Не удалось сериализовать данные транзакции";
        }
        Log::channel(ChannelLog::INFO->value)->info("{$message}\nТип:{$type->label()}\n\n" . $data);
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
