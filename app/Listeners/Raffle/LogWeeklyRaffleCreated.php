<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Enums\ChannelLog;
use App\Events\Raffle\WeeklyRaffleCreated;
use Illuminate\Support\Facades\Log;

final class LogWeeklyRaffleCreated
{

    public function handle(WeeklyRaffleCreated $event): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Был создан еженедельный розыгрыш', [
            'raffle_id' => $event->raffle->getKey(),
            'prize' => $event->raffle->prize,
        ]);
    }
}
