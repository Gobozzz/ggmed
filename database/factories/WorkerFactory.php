<?php

namespace Database\Factories;

use App\Models\Filial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "image" => "workers/2026-01/1.png",
            "surname" => fake()->name(),
            "name" => fake()->firstName(),
            "patronymic" => rand(0, 3) ? fake()->lastName() : null,
            "post" => fake()->words(3, true),
            "filial_id" => rand(0, 1) ? Filial::query()->inRandomOrder()->first() ?? Filial::factory()->create() : null,
        ];
    }
}
