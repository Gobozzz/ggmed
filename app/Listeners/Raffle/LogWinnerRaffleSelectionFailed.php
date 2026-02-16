<?php

declare(strict_types=1);

namespace App\Listeners\Raffle;

use App\Events\Raffle\WinnerRaffleSelectionFailed;
use Illuminate\Support\Facades\Log;

final class LogWinnerRaffleSelectionFailed
{
    public function handle(WinnerRaffleSelectionFailed $event): void
    {
        Log::error('Не удалось установить победителя розыгрыша', [
            'message' => $event->errorMessage,
            'winner_id' => $event->winner->getKey(),
            'raffle_id' => $event->raffle->getKey(),
            'type' => $event->raffle->type->label(),
            'prize' => $event->raffle->prize,
        ]);
    }
}
