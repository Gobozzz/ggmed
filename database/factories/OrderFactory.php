<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Payments\PaymentProvider;
use App\Enums\Payments\PaymentStatus;
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
            'user_id' => rand(0, 2) ? User::query()->inRandomOrder()->first() ?? User::factory()->create() : null,
            'customer_email' => fake()->email(),
            'customer_phone' => '+7'.rand(1000000000, 9999999999),
            'customer_name' => fake()->name(),
            'customer_city' => fake()->city(),
            'customer_street' => fake()->streetName(),
            'customer_house' => rand(1, 250),
            'total_amount' => fake()->randomFloat(2, 1000, 40000),
            'comment' => rand(0, 1) ? fake()->text(700) : null,
            'payment_provider' => PaymentProvider::CASH,
            'payment_status' => fake()->randomElement(PaymentStatus::cases()),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $count_positions = rand(1, 3);
            $products = Product::query()->inRandomOrder()->take($count_positions)->get();

            if ($products->count() < $count_positions) {
                $products = Product::factory($count_positions)->create();
            }

            $summa_for_one_position = $order->total_amount / $count_positions;

            foreach ($products as $product) {
                $quantity = rand(1, 3);
                OrderItem::create([
                    'product_id' => $product->getKey(),
                    'order_id' => $order->getKey(),
                    'quantity' => $quantity,
                    'price' => $summa_for_one_position / $quantity,
                    'old_price' => rand(0, 1) ? $summa_for_one_position + rand(100, 700) : null,
                    'article' => $product->article,
                    'title' => $product->title,
                    'image' => $product->images[0],
                ]);
            }

        });
    }
}
