<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => mb_ucfirst(fake()->words(rand(1, 3), true)),
            'slug' => fake()->unique()->slug(),
            'content' => json_encode(EditorGenerator::make(20)),
            'meta_title' => rand(0, 1) ? fake()->text(50) : null,
            'meta_description' => rand(0, 1) ? fake()->text(150) : null,
            'meta_robots' => rand(0, 1) ? 'index' : 'noindex',
            'og_image' => rand(0, 1) ? 'lorem.png' : null,
        ];
    }
}
