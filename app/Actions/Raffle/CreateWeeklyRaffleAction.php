<?php

declare(strict_types=1);

namespace App\Actions\Raffle;

use App\DTO\Raffle\CreateRaffleDTO;
use App\Enums\ChannelLog;
use App\Enums\RaffleType;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;

final class CreateWeeklyRaffleAction
{
    public function __construct(
        private readonly RaffleRepositoryContract $raffleRepository,
    )
    {
    }

    public function execute(): void
    {
        $data = new CreateRaffleDTO(
            type: RaffleType::WEEKLY,
            title: 'Еженедельный пятничный розыгрыш GG COIN',
            description: 'Приглашаем всех участников принять участие в еженедельном розыгрыше GG COIN! Получайте шанс выиграть дополнительные монеты каждую неделю. Участвуйте и выигрывайте!',
            date_end: Carbon::now()->next(CarbonInterface::FRIDAY),
            prize: ['amount' => rand(3, 5)],
        );

        try {
            $new_raffle = $this->raffleRepository->create($data);
            Log::channel(ChannelLog::INFO->value)->info('Еженедельный розыгрыш создан', ['raffle_id' => $new_raffle->getKey()]);
        } catch (\Exception $exception) {
            Log::error('Не удалось создать еженедельный розыгрыш', ['exception' => $exception->getMessage()]);
        }
    }
}
