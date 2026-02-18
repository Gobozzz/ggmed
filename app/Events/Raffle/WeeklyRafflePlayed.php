<?php

declare(strict_types=1);

namespace App\Events\Raffle;

use App\Models\Raffle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WeeklyRafflePlayed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly Raffle $raffle;

    public function __construct(Raffle $raffle)
    {
        $this->raffle = $raffle;
    }
}
