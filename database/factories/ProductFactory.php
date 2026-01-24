<?php

declare(strict_types=1);

namespace Database\Factories;

use App\FakeGenerators\EditorGenerator;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 3000, 120000);
        return [
            "meta_title" => rand(0, 1) ? fake()->text(60) : null,
            "meta_description" => rand(0, 1) ? fake()->text(500) : null,
            "title" => fake()->text(60),
            "description" => fake()->text(500),
            "images" => ['products/2026-01/1.png', 'products/2026-01/2.png', 'products/2026-01/3.png'],
            "price" => $price,
            "old_price" => rand(0, 1) ? $price + rand(1000, 5000) : null,
            "structure" => fake()->text(50),
            "brand" => fake()->randomElement(["GGMED", fake()->text(50)]),
            "is_have" => rand(0, 1),
            "content" => rand(0, 2) ? json_encode(EditorGenerator::make(15)) : null,
        ];
    }

    public function configure(): Factory
    {
        return $this->afterCreating(function (Product $product) {
            Comment::factory()->count(30)->create([
                'commentable_type' => Product::class,
                'commentable_id' => $product->getKey(),
            ]);

            $users_liked = User::query()->inRandomOrder()->limit(rand(1, 5))->get();

            if ($users_liked->isEmpty()) {
                $users_liked = User::factory()->create(rand(1, 5));
            }

            foreach ($users_liked as $user) {
                Like::factory()->create([
                    'user_id' => $user->getKey(),
                    'likeable_type' => Product::class,
                    'likeable_id' => $product->getKey(),
                ]);
            }

            $tags = Tag::query()->inRandomOrder()->limit(rand(1, 3))->get();

            if ($tags->isEmpty()) {
                $tags = Tag::factory()->count(rand(1, 3))->create();
            }

            $product->tags()->attach($tags->pluck('id')->toArray());
        });
    }


}
