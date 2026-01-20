<?php

namespace Database\Factories;

use App\Models\MoonshineUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Filial>
 */
class FilialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        return [
            "meta_title" => fake()->realText(120),
            "meta_description" => fake()->realText(500),
            "slug" => fake()->unique()->slug(),
            "name" => $name,
            "video" => rand(0, 1) ? "/filials-videos/1.mp4" : null,
            "image" => 'filials/1.png',
            "year" => rand(2020, 2026),
            "address" => fake()->address(),
            "work_time" => "Пн-Пт, 09:00-20:00",
            "map_code" => '<iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A28525d3136bf037fe44d77048907d41b5924cae33beae7096ee16b6ef89b9dd9&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>',
            "manager_id" => MoonshineUser::query()->inRandomOrder()->where('moonshine_user_role_id', MoonshineUser::FILIAL_MANAGER_ROLE_ID)->first(),
        ];
    }
}
