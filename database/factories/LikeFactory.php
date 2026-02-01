<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
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
            'user_id' => User::query()->inRandomOrder()->first() ?? User::factory()->create(),
            'likeable_type' => null,
            'likeable_id' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
