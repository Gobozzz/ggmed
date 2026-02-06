<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recommendation>
 */
class RecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'lorem.png',
            'title' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->realText(255),
            'meta_title' => fake()->words(3, true),
            'meta_description' => fake()->realText(160),
            'content' => json_encode(EditorGenerator::make(15)),
        ];
    }
}
