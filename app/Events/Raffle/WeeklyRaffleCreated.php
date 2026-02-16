<?php

declare(strict_types=1);

namespace App\Events\Raffle;

use App\Models\Raffle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WeeklyRaffleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Raffle $raffle)
    {
    }
}
