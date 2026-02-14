<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

use App\Actions\Raffle\SelectWinnerRaffleAction;
use App\Cache\BalanceCacheManager;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Enums\ChannelLog;
use App\Exceptions\Raffle\IncorrectPrizeException;
use App\Exceptions\Raffle\NoSetWinnerException;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\TransactionService\TransactionServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RaffleService implements RaffleServiceContract
{
    public function __construct(
        private readonly RaffleRepositoryContract   $raffleRepository,
        private readonly UserRepositoryContract     $userRepository,
        private readonly TransactionServiceContract $transactionService,
        private readonly BalanceCacheManager        $balanceCacheManager,
        private readonly SelectWinnerRaffleAction   $selectWinnerRaffleAction,
    )
    {
    }

    public function playWeeklyRaffle(): void
    {
        $raffle = $this->raffleRepository->getWeeklyReadyPlaying();
        if ($raffle === null) {
            Log::channel(ChannelLog::INFO->value)->info('Не найден еженедельный розыгрыш');

            return;
        }
        $winner = $this->selectWinnerRaffleAction->execute($raffle->getKey());

        if ($winner === null) {
            Log::channel(ChannelLog::INFO->value)->info('Не смогли определить победителя еженедельного розыгрыша');

            return;
        }

        if (!isset($raffle->prize['amount'])) {
            throw new IncorrectPrizeException;
        }

        DB::transaction(function () use ($winner, $raffle) {
            $this->userRepository->lockForUpdateById($winner->getKey());

            DB::afterRollBack(function () use ($raffle, $winner) {
                Log::error('Произошла ошибка при установке победителя розыгрыша', [
                    'winner_id' => $winner->getKey(),
                    'raffle_id' => $raffle->getKey(),
                ]);
            });

            if (!$this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey())) {
                throw new NoSetWinnerException;
            }

            $transaction = $this->transactionService->winningWeeklyRaffle(
                new WinningWeeklyRaffleDTO(
                    user_id: $winner->getKey(),
                    raffle_id: $raffle->getKey(),
                    amount: (float)$raffle->prize['amount'],
                )
            );

            DB::afterCommit(function () use ($winner, $raffle, $transaction) {
                $this->balanceCacheManager->forget($winner->getKey());
                Log::channel(ChannelLog::INFO->value)->info('Установлен победитель еженедельного розыгрыша', [
                    'raffle_id' => $raffle->getKey(),
                    'winner_id' => $winner->getKey(),
                    'transaction_id' => $transaction->getKey(),
                    'amount' => (float)$raffle->prize['amount'],
                ]);
            });

        }, config('transactions.count_attempts_transaction'));
    }

}
