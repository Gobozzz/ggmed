<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Enums\ChannelLog;
use App\Events\Raffle\NotFoundWinnerRaffle;
use Illuminate\Support\Facades\Log;

final class LogNoFoundWinnerRaffle
{
    public function handle(NotFoundWinnerRaffle $event): void
    {
        Log::channel(ChannelLog::INFO->value)->info('Не смогли определить победителя розыгрыша', [
            'raffle_id' => $event->raffle->getKey(),
            'type' => $event->raffle->type->label(),
        ]);
    }
}
