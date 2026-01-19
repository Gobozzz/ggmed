<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Result;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\Laravel\Models\MoonshineUser;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MoonshineUser::factory()->create([
            'name' => 'Admin GGMED',
            'email' => 'admin@ggmed.ru',
            'password' => Hash::make('ggmed_14&01&2026!'),
        ]);

        User::factory(30)->create();

        Tag::factory(30)->create();

        Result::factory(30)->create();

        Question::factory(30)->create();

        $parent_services = Service::factory(10)->create();
        foreach ($parent_services as $parent_service) {
            Service::factory(3)->withParent($parent_service)->create();
        }

    }
}
