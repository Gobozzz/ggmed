<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Filial;
use App\Models\MoonshineUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use MoonShine\Laravel\Models\MoonshineUserRole;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(2, true),
            'content' => rand(0, 1) ? fake()->realText() : null,
            'address' => fake()->address(),
            'url' => rand(0, 1) ? fake()->url() : null,
            'salary' => rand(0, 1) ? rand(90000, 350000) : null,
            'valute' => '₽',
            'what_pay' => 'в месяц',
            'responsible' => 'Гобозов Богдан, +79187094256',
            'author_id' => rand(0, 1) ? MoonshineUser::query()
                ->inRandomOrder()
                ->whereIn('moonshine_user_role_id', [MoonshineUserRole::DEFAULT_ROLE_ID, MoonshineUser::FILIAL_MANAGER_ROLE_ID])->first() ??
                MoonshineUser::factory()->create(['moonshine_user_role_id' => MoonshineUser::FILIAL_MANAGER_ROLE_ID]) : null,
            'filial_id' => rand(0, 1) ? Filial::query()->inRandomOrder()->first() ?? Filial::factory()->create() : null,
        ];
    }
}
