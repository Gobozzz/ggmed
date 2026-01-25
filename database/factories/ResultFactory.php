<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Result;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $panchs = [0.65, 1, 1.3, 2];

        return [
            'images' => ['results/2026-01/1.jpg', 'results/2026-01/2.jpg', 'results/2026-01/3.jpg', 'results/2026-01/4.jpg'],
            'count_grafts' => rand(0, 4) ? rand(15, 50) * 100 : null,
            'count_months' => rand(0, 4) ? rand(5, 16) : null,
            'panch' => rand(0, 4) ? fake()->randomElement($panchs) : null,
            'video_url' => rand(0, 4) ? fake()->url() : null,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Result $result) {
            Comment::factory()->count(30)->create([
                'commentable_type' => Result::class,
                'commentable_id' => $result->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Result::class,
                    'likeable_id' => $result->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $result->tags()->attach($tags->pluck('id')->toArray());
        });
    }
}
