<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

use App\Actions\Raffle\CreateWeeklyRaffleAction;
use App\Actions\Raffle\ProcessSetWinnerWeeklyRaffleAction;
use App\Actions\Raffle\SelectWinnerRaffleAction;
use App\Cache\BalanceCacheManager;
use App\Enums\ChannelLog;
use App\Exceptions\Raffle\IncorrectPrizeException;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Illuminate\Support\Facades\Log;

final class RaffleService implements RaffleServiceContract
{
    public function __construct(
        private readonly RaffleRepositoryContract           $raffleRepository,
        private readonly BalanceCacheManager                $balanceCacheManager,
        private readonly SelectWinnerRaffleAction           $selectWinnerRaffleAction,
        private readonly ProcessSetWinnerWeeklyRaffleAction $processSetWinnerWeeklyRaffleAction,
        private readonly CreateWeeklyRaffleAction           $createWeeklyRaffleAction,
    )
    {
    }

    public function createWeeklyRaffle(): void
    {
        $result = $this->createWeeklyRaffleAction->execute();
        if ($result->success) {
            Log::channel(ChannelLog::INFO->value)->info("Был создан еженедельный розыгрыш", [
                'raffle_id' => $result->raffle_id,
            ]);
        } else {
            Log::error('Не удалось создать еженедельный розыгрыш', ['message' => $result->error_message]);
        }
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

        $resultSet = $this->processSetWinnerWeeklyRaffleAction->execute($winner, $raffle);

        if ($resultSet->success) {
            $this->balanceCacheManager->forget($winner->getKey());
            Log::channel(ChannelLog::INFO->value)->info('Победитель еженедельного розыгрыша установлен', [
                'winner_id' => $resultSet->winner_id,
                'raffle_id' => $resultSet->raffle_id,
                'amount' => $resultSet->amount,
            ]);
        } else {
            Log::error('Не удалось установить победителя для еженедельного розыгрыша', [
                'message' => $resultSet->error_message,
                'winner_id' => $resultSet->winner_id,
                'raffle_id' => $resultSet->raffle_id,
                'amount' => $resultSet->amount,
            ]);
        }

    }
}
