<?php

namespace App\Services\RaffleService;

use App\Cache\BalanceCacheManager;
use App\DTO\Raffle\CreateRaffleDTO;
use App\DTO\Transaction\WinningRaffleDTO;
use App\Enums\ChannelLog;
use App\Enums\RaffleType;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\TransactionService\TransactionServiceContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RaffleService implements RaffleServiceContract
{
    public function __construct(
        private readonly RaffleRepositoryContract $raffleRepository,
        private readonly UserRepositoryContract $userRepository,
        private readonly TransactionServiceContract $transactionService,
        private readonly BalanceCacheManager $balanceCacheManager,
    ) {}

    public function createWeekly(): void
    {
        $data = new CreateRaffleDTO(
            type: RaffleType::WEEKLY,
            title: 'Еженедельный пятничный розыгрыш GG COIN',
            description: 'Приглашаем всех участников принять участие в еженедельном розыгрыше GG COIN! Получайте шанс выиграть дополнительные монеты каждую неделю. Участвуйте и выигрывайте!',
            date_end: Carbon::now()->next(Carbon::FRIDAY)->format('Y-m-d'),
            prize: ['amount' => rand(3, 5)],
        );

        try {
            $new_raffle = $this->raffleRepository->create($data);
            Log::channel(ChannelLog::INFO->value)->info('Еженедельный розыгрыш создан', ['raffle_id' => $new_raffle->getKey()]);
        } catch (\Exception $exception) {
            Log::error('Не удалось создать еженедельный розыгрыш', ['exception' => $exception->getMessage()]);
        }
    }

    public function playWeeklyRaffle(): void
    {
        $raffle = $this->raffleRepository->getWeeklyReadyPlaying();
        if ($raffle === null) {
            Log::channel(ChannelLog::INFO->value)->info('Не найден еженедельный розыгрыш');

            return;
        }
        $winner = $this->userRepository->getRandomParticipantForRaffle($raffle->getKey());
        if ($winner === null) {
            Log::channel(ChannelLog::INFO->value)->info('Не удалось найти победителя для еженедельного розыгрыша', [
                'raffle_id' => $raffle->getKey(),
            ]);

            return;
        }
        DB::transaction(function () use ($winner, $raffle) {
            $this->userRepository->lockForUpdateById($winner->getKey());

            DB::afterRollBack(function () {
                Log::channel(ChannelLog::INFO->value)->info('При установке победителя розыгрыша транзакция упала');
            });

            $this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey());

            $transaction = $this->transactionService->winningRaffle(
                new WinningRaffleDTO(
                    user_id: $winner->getKey(),
                    raffle_id: $raffle->getKey(),
                    amount: (float) $raffle->prize['amount'],
                )
            );

            DB::afterCommit(function () use ($winner, $raffle, $transaction) {
                $this->balanceCacheManager->forget($winner->getKey());
                Log::channel(ChannelLog::INFO->value)->info('Установлен победитель еженедельного розыгрыша', [
                    'raffle_id' => $raffle->getKey(),
                    'winner_id' => $winner->getKey(),
                    'transaction_id' => $transaction->getKey(),
                    'amount' => (float) $raffle->prize['amount'],
                ]);
            });

        }, config('transactions.count_attempts_transaction'));
    }
}
