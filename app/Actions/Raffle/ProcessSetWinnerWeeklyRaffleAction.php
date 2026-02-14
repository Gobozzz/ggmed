<?php

declare(strict_types=1);

namespace App\Actions\Raffle;

use App\DTO\Raffle\ResultSetWinnerWeeklyDTO;
use App\DTO\Transaction\WinningWeeklyRaffleDTO;
use App\Exceptions\Raffle\NoSetWinnerException;
use App\Models\Raffle;
use App\Models\User;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use App\Repositories\UserRepository\UserRepositoryContract;
use App\Services\TransactionService\TransactionServiceContract;
use Illuminate\Support\Facades\DB;

final class ProcessSetWinnerWeeklyRaffleAction
{
    public function __construct(
        private readonly UserRepositoryContract $userRepository,
        private readonly RaffleRepositoryContract $raffleRepository,
        private readonly TransactionServiceContract $transactionService,
    ) {}

    public function execute(User $winner, Raffle $raffle): ResultSetWinnerWeeklyDTO
    {
        try {
            return DB::transaction(function () use ($winner, $raffle) {
                $this->userRepository->lockForUpdateById($winner->getKey());

                if (! $this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey())) {
                    throw new NoSetWinnerException;
                }

                $this->transactionService->winningWeeklyRaffle(
                    new WinningWeeklyRaffleDTO(
                        user_id: $winner->getKey(),
                        raffle_id: $raffle->getKey(),
                        amount: (float) $raffle->prize['amount'],
                    )
                );

                return new ResultSetWinnerWeeklyDTO(
                    success: true,
                    winner_id: $winner->getKey(),
                    raffle_id: $raffle->getKey(),
                    amount: (float) $raffle->prize['amount'],
                );

            }, config('transactions.count_attempts_transaction'));
        } catch (\Exception $e) {
            return new ResultSetWinnerWeeklyDTO(
                success: false,
                winner_id: $winner->getKey(),
                raffle_id: $raffle->getKey(),
                amount: (float) $raffle->prize['amount'],
                error_message: $e->getMessage(),
            );
        }
    }
}
