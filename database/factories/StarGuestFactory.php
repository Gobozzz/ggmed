<?php

declare(strict_types=1);

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StarGuest>
 */
class StarGuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meta_title' => rand(0, 1) ? fake()->text(100) : null,
            'meta_description' => rand(0, 1) ? fake()->text(160) : null,
            'slug' => fake()->unique()->slug(),
            'name' => fake()->lastName().' '.fake()->name(),
            'points' => json_encode([fake()->text(60), fake()->text(90), fake()->text(40), fake()->text(100)]),
            'url' => fake()->url(),
            'content' => rand(0, 2) ? json_encode(EditorGenerator::make(15)) : null,
            'image' => 'lorem.png',
        ];
    }
}
