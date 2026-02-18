<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

use App\Actions\Raffle\SelectWinnerRaffleAction;
use App\DTO\Transaction\PayPrizeRaffleDTO;
use App\Events\Raffle\NotFoundReadyWeeklyRaffle;
use App\Events\Raffle\NotFoundWinnerRaffle;
use App\Events\Raffle\WeeklyRafflePlayed;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\TransactionService\TransactionServiceContract;
use Illuminate\Support\Facades\DB;

final class RaffleService implements RaffleServiceContract
{
    public function __construct(
        private readonly UserRepositoryContract     $userRepository,
        private readonly RaffleRepositoryContract   $raffleRepository,
        private readonly SelectWinnerRaffleAction   $selectWinnerRaffleAction,
        private readonly TransactionServiceContract $transactionService,
    )
    {
    }

    public function playWeeklyRaffle(): void
    {
        $raffle = $this->raffleRepository->getWeeklyReadyPlayingNow();

        if ($raffle === null) {
            event(new NotFoundReadyWeeklyRaffle);

            return;
        }
        $winner = $this->selectWinnerRaffleAction->execute($raffle->getKey());

        if ($winner === null) {
            event(new NotFoundWinnerRaffle($raffle));

            return;
        }

        DB::transaction(function () use ($winner, $raffle) {
            $this->userRepository->getByIdAndLock($winner->getKey());

            $this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey());

            $this->transactionService->payPrizeRaffle(
                new PayPrizeRaffleDTO(
                    userId: $winner->getKey(),
                    raffleId: $raffle->getKey(),
                    amount: $raffle->getPrizeAmount(),
                )
            );

        }, config('raffle.count_attempts_transaction'));

        event(new WeeklyRafflePlayed($raffle));
    }
}
