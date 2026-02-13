<?php

declare(strict_types=1);

namespace App\Console\Commands\Raffle;

use App\Enums\ChannelLog;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class ClearWeeklyUnplayedRaffles extends Command
{
    protected $signature = 'raffle:clear-weekly-unplayed-raffles';

    protected $description = 'Команда для очистки неразыгранных еженедельных розыгрышей';

    public function __construct(
        private readonly RaffleRepositoryContract $raffleRepository,
    ) {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel(ChannelLog::INFO->value)->info('Запущена команда очистки неразыгранных еженедельных розыгрышей');
        $this->raffleRepository->deleteAllWeeklyUnplayed();
    }
}
