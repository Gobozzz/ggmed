<?php

declare(strict_types=1);

namespace App\Actions\Raffle;

use App\DTO\Raffle\CreateRaffleDTO;
use App\Enums\RaffleType;
use App\Events\Raffle\WeeklyRaffleCreated;
use App\Models\Raffle;
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

    public function execute(): Raffle
    {
        $data = new CreateRaffleDTO(
            type: RaffleType::WEEKLY,
            title: 'Еженедельный пятничный розыгрыш GG COIN',
            description: 'Приглашаем всех участников принять участие в еженедельном розыгрыше GG COIN! Получайте шанс выиграть дополнительные монеты каждую неделю. Участвуйте и выигрывайте!',
            dateEnd: Carbon::now()->next(CarbonInterface::FRIDAY),
            prize: ['amount' => rand(
                config('raffle.weekly.min_prize'),
                config('raffle.weekly.max_prize'),
            )],
        );

        $raffle = $this->raffleRepository->create($data);

        event(new WeeklyRaffleCreated($raffle));

        return $raffle;
    }
}
