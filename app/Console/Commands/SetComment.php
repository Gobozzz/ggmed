<?php

namespace App\Console\Commands;

use App\Models\Raffle;
use App\Models\User;
use Illuminate\Console\Command;

class SetComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $raffle = Raffle::query()->orderByDesc('id')->first();
        $raffle->comments()->create([
            'user_id' => User::query()->inRandomOrder()->first()->getKey(),
            'content' => "Loremchik",
        ]);
    }
}
