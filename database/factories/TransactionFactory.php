<?php

namespace Database\Factories;

use App\Enums\TypeTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Создаем пользователя, если нет
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();

        // Генерируем случайную сумму (от -1000 до 1000)
        $amount = $this->faker->randomFloat(2, -1000, 1000);

        // Определяем тип на основе суммы
        $type = $amount >= 0 ? TypeTransaction::ADMIN_REPLENISHED : TypeTransaction::ADMIN_WRITE_OFF;

        // Генерируем случайную дату в пределах последних 180 дней
        $createdAt = $this->faker->dateTimeBetween('-180 days');

        return [
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'description' => $this->faker->sentence(6),
            'metadata' => ['some' => 'data'],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
