<?php

declare(strict_types=1);

namespace App\Events\Raffle;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WeeklyRaffleCreationFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $errorMessage;

    public function __construct(
        string $errorMessage,
    )
    {
        $this->errorMessage = $errorMessage;
    }

}
