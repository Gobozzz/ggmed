<?php

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(500),
            'answer' => rand(0, 1) ? json_encode(EditorGenerator::make(10)) : null,
            'user_id' => User::query()->inRandomOrder()->first() ?? null,
            'is_hot' => rand(0, 1),
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Question $question) {
            Comment::factory()->count(rand(1, 5))->create([
                'commentable_type' => Question::class,
                'commentable_id' => $question->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Question::class,
                    'likeable_id' => $question->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $question->tags()->attach($tags->pluck('id')->toArray());

        });
    }
}
