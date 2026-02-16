<?php

declare(strict_types=1);

namespace App\Events\Raffle;

use App\Models\Raffle;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WinnerRaffleSelectionFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly string $errorMessage;

    public readonly Raffle $raffle;

    public readonly User $winner;

    public function __construct(string $errorMessage, Raffle $raffle, User $winner)
    {
        $this->errorMessage = $errorMessage;
        $this->raffle = $raffle;
        $this->winner = $winner;
    }
}
