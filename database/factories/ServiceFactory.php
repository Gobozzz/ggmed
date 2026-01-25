<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use App\Models\Service;
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
            'meta_title' => fake()->realText(120),
            'meta_description' => fake()->realText(500),
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => rand(2000, 190000),
            'image' => 'services/2026-01/1.jpg',
            'content' => json_encode(EditorGenerator::make(10)),
            'description' => fake()->realText(255),
            'is_start_price' => (bool) rand(0, 2),
            'parent_id' => null,
        ];
    }

    public function withParent(Service|int|null $parent = null): static
    {
        return $this->state(function (array $attributes) use ($parent) {
            if ($parent === null) {
                $existingParent = Service::query()->whereNull('parent_id')->inRandomOrder()->first() ??
                    Service::factory()->create();

                return [
                    'parent_id' => $existingParent ?? $existingParent->getKey(),
                ];
            }

            return [
                'parent_id' => $parent instanceof Service ? $parent->getKey() : $parent,
            ];
        });
    }
}
