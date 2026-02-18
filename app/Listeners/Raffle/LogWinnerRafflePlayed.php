<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Enums\ChannelLog;
use App\Events\Raffle\WeeklyRafflePlayed;
use Illuminate\Support\Facades\Log;

final class LogWinnerRafflePlayed
{
    public function handle(WeeklyRafflePlayed $event): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Победитель розыгрыша установлен', [
            'winner_id' => $event->raffle->winner_id,
            'raffle_id' => $event->raffle->getKey(),
            'type' => $event->raffle->type->label(),
            'prize' => $event->raffle->prize,
        ]);
    }
}
