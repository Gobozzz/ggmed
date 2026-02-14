<?php

declare(strict_types=1);

namespace App\Actions\Raffle;

use App\DTO\Raffle\CreateRaffleDTO;
use App\DTO\Raffle\ResultCreateWeeklyDTO;
use App\Enums\RaffleType;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Carbon\Carbon;
use Carbon\CarbonInterface;

final class CreateWeeklyRaffleAction
{
    public function __construct(
        private readonly RaffleRepositoryContract $raffleRepository,
    )
    {
    }

    public function execute(): ResultCreateWeeklyDTO
    {
        $data = new CreateRaffleDTO(
            type: RaffleType::WEEKLY,
            title: 'Еженедельный пятничный розыгрыш GG COIN',
            description: 'Приглашаем всех участников принять участие в еженедельном розыгрыше GG COIN! Получайте шанс выиграть дополнительные монеты каждую неделю. Участвуйте и выигрывайте!',
            date_end: Carbon::now()->next(CarbonInterface::FRIDAY),
            prize: ['amount' => rand(3, 5)],
        );

        try {
            $raffle = $this->raffleRepository->create($data);
            return new ResultCreateWeeklyDTO(success: true, raffle_id: $raffle->getKey());
        } catch (\Exception $exception) {
            return new ResultCreateWeeklyDTO(success: false, error_message: $exception->getMessage());
        }
    }
}
