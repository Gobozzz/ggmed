<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Raffle;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Raffle>
 */
class RaffleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date_end = rand(0, 1) ? Carbon::now()->addDays(rand(5, 30)) : Carbon::now()->subDays(rand(0, 10));

        return [
            'title' => fake()->text(100),
            'description' => fake()->text(255),
            'slug' => fake()->unique()->slug(),
            'meta_title' => rand(0, 1) ? fake()->text(100) : null,
            'meta_description' => rand(0, 1) ? fake()->text(160) : null,
            'content' => json_encode(EditorGenerator::make(15)),
            'image' => rand(0, 1) ? 'raffles/2026-01/1.png' : null,
            'video' => rand(0, 1) ? 'raffles-videos/2026-01/1.png' : null,
            'winner_id' => $date_end > Carbon::now() ? User::query()->inRandomOrder()->first() ?? User::factory()->create() : null,
            'date_end' => $date_end,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Raffle $raffle) {
            Comment::factory()->count(30)->create([
                'commentable_type' => Raffle::class,
                'commentable_id' => $raffle->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(12, 15))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(12, 15));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Raffle::class,
                    'likeable_id' => $raffle->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $raffle->tags()->attach($tags->pluck('id')->toArray());
        });
    }
}
