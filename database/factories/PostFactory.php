<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use App\Models\Comment;
use App\Models\Filial;
use App\Models\Like;
use App\Models\MoonshineUser;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
            'title' => fake()->text(100),
            'description' => fake()->text(255),
            'slug' => fake()->unique()->slug(),
            'image' => 'posts/2026-01/1.png',
            'content' => json_encode(EditorGenerator::make(20)),
            'time_to_read' => rand(5, 30),
            'filial_id' => rand(0, 2) ? null : Filial::query()->inRandomOrder()->first() ?? Filial::factory()->create(),
            'author_id' => rand(0, 2) ? MoonshineUser::query()->inRandomOrder()->first() ?? MoonshineUser::factory()->create() : null,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Post $post) {
            Comment::factory()->count(rand(1, 5))->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Post::class,
                    'likeable_id' => $post->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $post->tags()->attach($tags->pluck('id')->toArray());

        });
    }
}
