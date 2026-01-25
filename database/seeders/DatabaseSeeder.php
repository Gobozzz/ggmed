<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Fact;
use App\Models\Filial;
use App\Models\Mention;
use App\Models\MoonshineUser;
use App\Models\Order;
use App\Models\Post;
use App\Models\PostSeries;
use App\Models\Product;
use App\Models\Question;
use App\Models\Recommendation;
use App\Models\Result;
use App\Models\Service;
use App\Models\StarGuest;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\VideoReview;
use App\Models\Worker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\Laravel\Models\MoonshineUserRole;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MoonshineUserRole::create([
            'id' => MoonshineUser::FILIAL_MANAGER_ROLE_ID,
            'name' => 'Управляющий филиалом',
        ]);
        MoonshineUserRole::create([
            'id' => MoonshineUser::AUTHOR_POSTS_ROLE_ID,
            'name' => 'Автор статей',
        ]);
        MoonshineUser::factory()->create([
            'name' => 'GGMED',
            'email' => 'admin@ggmed.ru',
            'password' => Hash::make('ggmed_14&01&2026!'),
        ]);
        MoonshineUser::factory()->create([
            'name' => 'Гобозов Богдан',
            'email' => 'gobozovbogdan@gmail.com',
            'password' => Hash::make('ggmed_14&01&2026!'),
            'moonshine_user_role_id' => MoonshineUser::FILIAL_MANAGER_ROLE_ID,
        ]);
        MoonshineUser::factory()->create([
            'name' => 'Геворгизов Артур',
            'email' => 'author@gmail.com',
            'password' => Hash::make('ggmed_14&01&2026!'),
            'moonshine_user_role_id' => MoonshineUser::AUTHOR_POSTS_ROLE_ID,
        ]);

        User::factory(30)->create();

        Tag::factory(30)->create();

        Result::factory(30)->create();

        Question::factory(30)->create();

        $parent_services = Service::factory(10)->create();
        foreach ($parent_services as $parent_service) {
            Service::factory(3)->withParent($parent_service)->create();
        }

        Filial::factory(10)->create();

        VideoReview::factory(10)->create();

        Post::factory(30)->create();

        PostSeries::factory(10)->create();

        Recommendation::factory(10)->create();

        Mention::factory(10)->create();

        Worker::factory(30)->create();

        StarGuest::factory(15)->create();

        DocumentCategory::factory(5)->create();

        Document::factory(12)->create();

        Product::factory(30)->create();

        Order::factory(30)->create();

        Vacancy::factory(30)->create();

        Fact::factory(10)->create();

    }
}
