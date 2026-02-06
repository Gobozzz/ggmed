<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostSeries>
 */
class PostSeriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'title' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->text(255),
            'image' => 'lorem.png',
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (PostSeries $series) {
            $posts = Post::query()->inRandomOrder()->limit(rand(3, 5))->get();
            if ($posts->isEmpty()) {
                $posts = Post::factory(rand(3, 5))->create();
            }
            $series->posts()->attach($posts->pluck('id')->toArray());
        });
    }
}
