<?php

declare(strict_types=1);

namespace App\Console\Commands\Raffle;

use App\Enums\ChannelLog;
use App\Services\RaffleService\RaffleServiceContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class CreateWeeklyRaffleCommand extends Command
{
    protected $signature = 'raffle:create-weekly';

    protected $description = 'Создание еженедельного розыгрыша';

    public function __construct(
        private readonly RaffleServiceContract $raffleService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Запущена команда создания еженедельного розыгрыша');
        $this->raffleService->createWeekly();
    }
}
