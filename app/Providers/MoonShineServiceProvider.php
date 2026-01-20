<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\MoonshineUser;
use App\Models\Question;
use App\Models\Result;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use App\MoonShine\Resources\Question\QuestionResource;
use App\Policies\MoonShine\CommentPolicy;
use App\Policies\MoonShine\LikePolicy;
use App\Policies\MoonShine\MoonshineUserPolicy;
use App\Policies\MoonShine\QuestionPolicy;
use App\Policies\MoonShine\ResultPolicy;
use App\Policies\MoonShine\ServicePolicy;
use App\Policies\MoonShine\TagPolicy;
use App\Policies\MoonShine\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\Result\ResultResource;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\Filial\FilialResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    /**
     * @param CoreContract<MoonShineConfigurator> $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                ResultResource::class,
                UserResource::class,
                CommentResource::class,
                LikeResource::class,
                QuestionResource::class,
                TagResource::class,
                ServiceResource::class,
                FilialResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ]);

        if (request()->is('admin*')) {
            Gate::policy(MoonshineUser::class, MoonshineUserPolicy::class);
            Gate::policy(User::class, UserPolicy::class);
            Gate::policy(Result::class, ResultPolicy::class);
            Gate::policy(Question::class, QuestionPolicy::class);
            Gate::policy(Service::class, ServicePolicy::class);
            Gate::policy(Tag::class, TagPolicy::class);
            Gate::policy(Comment::class, CommentPolicy::class);
            Gate::policy(Like::class, LikePolicy::class);
        }
    }
}
