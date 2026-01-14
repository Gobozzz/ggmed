<?php

namespace Database\Seeders;

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
            'email' => 'admin-files@ggmed.ru',
            'password' => Hash::make('ggmed_14&01&2026!'),
            'avatar' => '/admins/admin-files.jpg'
        ]);
    }
}
