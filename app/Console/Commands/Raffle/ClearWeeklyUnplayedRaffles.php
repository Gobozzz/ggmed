<?php

declare(strict_types=1);

namespace App\Console\Commands\Raffle;

use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Illuminate\Console\Command;

final class ClearWeeklyUnplayedRaffles extends Command
{
    protected $signature = 'raffle:clear-weekly-unplayed-raffles';

    protected $description = 'Команда для очистки неразыгранных еженедельных розыгрышей';

    public function __construct(
        private readonly RaffleRepositoryContract $raffleRepository,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->raffleRepository->deleteAllWeeklyUnplayed();
    }
}
