<?php

declare(strict_types=1);

namespace App\Console\Commands\Raffle;

use App\Enums\ChannelLog;
use App\Services\RaffleService\RaffleServiceContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class PlayWeeklyRaffleCommand extends Command
{
    protected $signature = 'raffle:play-weekly';

    protected $description = 'Команда разыгрывает еженедельный розыгрыш';

    public function __construct(
        private readonly RaffleServiceContract $raffleService,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Запущена команда для определения победителя еженедельного розыгрыша');
        $this->raffleService->playWeeklyRaffle();
    }
}
