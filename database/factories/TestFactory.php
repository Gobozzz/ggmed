<?php

namespace Database\Factories;

use App\Enums\TypeExercise;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Tag;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(5, true),
            'description' => fake()->text(255),
            'meta_title' => rand(0, 1) ? fake()->text(100) : null,
            'meta_description' => rand(0, 1) ? fake()->text(160) : null,
            'exercises' => [
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::THEORETICAL,
                    'answers' => [],
                ],
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::SINGLE,
                    'answers' => [
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                    ],
                ],
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::MULTIPLE,
                    'answers' => [
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                    ],
                ],
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::THEORETICAL,
                    'answers' => [],
                ],
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::SINGLE,
                    'answers' => [
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                    ],
                ],
                [
                    'title' => fake()->text(40),
                    'description' => fake()->text(255),
                    'type' => TypeExercise::MULTIPLE,
                    'answers' => [
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => false],
                        ['title' => fake()->words(rand(2, 5), true), 'trusty' => true],
                    ],
                ],
            ],
            'image' => 'tests/2026-01/1.png',
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Test $test) {
            Comment::factory()->count(30)->create([
                'commentable_type' => Test::class,
                'commentable_id' => $test->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Test::class,
                    'likeable_id' => $test->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $test->tags()->attach($tags->pluck('id')->toArray());
        });
    }
}
