<?php

use App\Console\Commands\Raffle\ClearWeeklyUnplayedRaffles;
use App\Console\Commands\Raffle\CreateWeeklyRaffleCommand;
use App\Console\Commands\Raffle\PlayWeeklyRaffleCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CreateWeeklyRaffleCommand::class)->weeklyOn(1, '21:00');
Schedule::command(PlayWeeklyRaffleCommand::class)->weeklyOn(5, '21:00');
Schedule::command(ClearWeeklyUnplayedRaffles::class)->dailyAt('23:00');
