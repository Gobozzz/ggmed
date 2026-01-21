<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Filial;
use App\Models\Like;
use App\Models\Tag;
use App\Models\User;
use App\Models\VideoReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoReview>
 */
class VideoReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "preview" => "video-reviews-previews/2026-01/1.png",
            "video" => "video-reviews/2026-01/1.png",
            "title" => fake()->text(50),
            "content" => fake()->text(500),
            "filial_id" => rand(0, 1) ? Filial::query()->inRandomOrder()->first() ?? Filial::factory()->create() : null,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (VideoReview $videoReview) {
            Comment::factory()->count(rand(1, 5))->create([
                'commentable_type' => VideoReview::class,
                'commentable_id' => $videoReview->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => VideoReview::class,
                    'likeable_id' => $videoReview->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $videoReview->tags()->attach($tags->pluck('id')->toArray());

        });
    }

}
