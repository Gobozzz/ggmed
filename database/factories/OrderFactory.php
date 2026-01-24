<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => rand(0, 2) ? User::query()->inRandomOrder()->first() ?? User::factory()->create() : null,
            "email" => fake()->email(),
            "phone" => "+7" . rand(1000000000, 9999999999),
            "name" => fake()->name(),
            "city" => fake()->city(),
            "street" => fake()->streetName(),
            "house" => rand(1, 250),
            "total_price" => fake()->randomFloat(2, 1000, 9999999.99),
            "count_positions" => rand(1, 5),
            "comment" => rand(0, 1) ? fake()->text(500) : null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::query()->inRandomOrder()->take($order->count_positions)->get();

            if ($products->count() < $order->count_positions) {
                $products = Product::factory($order->count_positions)->create();
            }

            $summa_for_one_position = $order->total_price / $order->count_positions;

            foreach ($products as $product) {
                OrderItem::create([
                    "product_id" => $product->getKey(),
                    "order_id" => $order->getKey(),
                    "quantity" => rand(1, 3),
                    "price" => $summa_for_one_position,
                    "old_price" => rand(0, 1) ? $summa_for_one_position + rand(100, 700) : null,
                    "article" => $product->article,
                    "title" => $product->title,
                    "image" => $product->images[0],
                ]);
            }

        });
    }

}
