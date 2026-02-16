<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Enums\ChannelLog;
use App\Events\Raffle\NotFoundReadyWeeklyRaffle;
use Illuminate\Support\Facades\Log;

final class LogNoFoundReadyWeeklyRaffle
{
    public function handle(NotFoundReadyWeeklyRaffle $event): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Не найден еженедельный розыгрыш готовый к игре');
    }
}
