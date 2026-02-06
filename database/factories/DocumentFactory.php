<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'file' => 'lorem.pdf',
            'document_category_id' => rand(0, 2) ? DocumentCategory::query()->inRandomOrder()->first() ?? DocumentCategory::factory()->create() : null,
        ];
    }
}
