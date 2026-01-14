<?php

namespace Database\Factories;

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
            "images" => ['results/2026-01/1.jpg', 'results/2026-01/2.jpg', 'results/2026-01/3.jpg', 'results/2026-01/4.jpg'],
            "count_grafts" => rand(0, 4) ? rand(15, 50) * 100 : null,
            "count_months" => rand(0, 4) ? rand(5, 16) : null,
            "panch" => rand(0, 4) ? array_rand($panchs) : null,
            "video_url" => rand(0, 4) ? fake()->url() : null,
        ];
    }
}
