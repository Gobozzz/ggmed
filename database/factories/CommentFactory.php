<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-180 days');

        return [
            'content' => $this->faker->text(350),
            'user_id' => User::query()->inRandomOrder()->first() ?? User::factory()->create(),
            'parent_id' => null,
            'commentable_type' => null,
            'commentable_id' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
