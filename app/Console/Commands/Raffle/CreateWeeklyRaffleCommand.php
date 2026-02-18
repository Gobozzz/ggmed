<?php

declare(strict_types=1);

namespace App\Console\Commands\Raffle;

use App\Actions\Raffle\CreateWeeklyRaffleAction;
use Illuminate\Console\Command;

final class CreateWeeklyRaffleCommand extends Command
{
    protected $signature = 'raffle:create-weekly';

    protected $description = 'Создание еженедельного розыгрыша';

    public function __construct(
        private readonly CreateWeeklyRaffleAction $createWeeklyRaffleAction,
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->createWeeklyRaffleAction->execute();
    }
}
