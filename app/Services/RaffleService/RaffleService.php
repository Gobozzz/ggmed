<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

use App\Actions\Raffle\CreateWeeklyRaffleAction;
use App\Actions\Raffle\SelectWinnerRaffleAction;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Events\Raffle\NotFoundReadyWeeklyRaffle;
use App\Events\Raffle\NotFoundWinnerRaffle;
use App\Events\Raffle\WeeklyRaffleCreationFailed;
use App\Events\Raffle\WinnerRaffleSelected;
use App\Events\Raffle\WinnerRaffleSelectionFailed;
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
        private readonly CreateWeeklyRaffleAction   $createWeeklyRaffleAction,
        private readonly TransactionServiceContract $transactionService,
    )
    {
    }

    public function createWeeklyRaffle(): void
    {
        try {
            $this->createWeeklyRaffleAction->execute();
        } catch (\Exception $e) {
            event(new WeeklyRaffleCreationFailed($e->getMessage()));
        }
    }

    public function playWeeklyRaffle(): void
    {
        $raffle = $this->raffleRepository->getWeeklyReadyPlaying();

        if ($raffle === null) {
            event(new NotFoundReadyWeeklyRaffle());

            return;
        }
        $winner = $this->selectWinnerRaffleAction->execute($raffle->getKey());

        if ($winner === null) {
            event(new NotFoundWinnerRaffle($raffle));

            return;
        }

        try {
            DB::transaction(function () use ($winner, $raffle) {
                $this->userRepository->getByIdAndLock($winner->getKey());

                $this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey());

                $this->transactionService->winningWeeklyRaffle(
                    new WinningWeeklyRaffleDTO(
                        userId: $winner->getKey(),
                        raffleId: $raffle->getKey(),
                        amount: $raffle->getPrizeAmount(),
                    )
                );

            }, config('transactions.count_attempts_transaction'));

            event(new WinnerRaffleSelected($raffle));
        } catch (\Throwable $e) {
            event(new WinnerRaffleSelectionFailed($e->getMessage(), $raffle, $winner));
        }

    }
}
