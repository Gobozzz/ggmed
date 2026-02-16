<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Events\Raffle\WeeklyRaffleCreationFailed;
use Illuminate\Support\Facades\Log;

final class LogWeeklyRaffleCreationFailed
{
    public function handle(WeeklyRaffleCreationFailed $event): void
    {
        Log::error('Не удалось создать еженедельный розыгрыш', ['message' => $event->errorMessage]);
    }
}
