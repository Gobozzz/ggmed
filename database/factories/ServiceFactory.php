<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            "name" => $name,
            "slug" => Str::slug($name),
            "price" => rand(2000, 190000),
            "image" => "services/2026-01/1.jpg",
            "content" => [
                'time' => 1275618756813,
                'verison' => '2.2',
                'blocks' => []
            ],
        ];
    }
}
