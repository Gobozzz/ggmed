<?php

declare(strict_types=1);

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(100),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->realText(255),
            'image' => 'lorem.png',
            'video' => rand(0, 1) ? 'lorem.mp4' : null,
            'meta_title' => rand(0, 1) ? fake()->text(100) : null,
            'meta_description' => rand(0, 1) ? fake()->text(160) : null,
            'content' => rand(0, 1) ? json_encode(EditorGenerator::make(10)) : null,
        ];
    }
}
