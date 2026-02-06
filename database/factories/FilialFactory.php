<?php

namespace Database\Factories;

use App\Models\MoonshineUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->unique()->company();

        return [
            'meta_title' => fake()->realText(100),
            'meta_description' => fake()->realText(160),
            'slug' => Str::slug($name),
            'name' => $name,
            'phone' => '+7'.rand(1000000000, 9999999999),
            'video' => rand(0, 1) ? 'lorem.mp4' : null,
            'image' => 'lorem.png',
            'year' => rand(2020, 2026),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'work_time' => 'Пн-Пт, 09:00-20:00',
            'map_code' => '<iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A28525d3136bf037fe44d77048907d41b5924cae33beae7096ee16b6ef89b9dd9&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>',
            'manager_id' => rand(0, 2) ? MoonshineUser::query()->inRandomOrder()->where('moonshine_user_role_id', MoonshineUser::FILIAL_MANAGER_ROLE_ID)->first() : null,
        ];
    }
}
