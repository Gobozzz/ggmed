<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Comment;
use App\Models\Filial;
use App\Models\Like;
use App\Models\Mention;
use App\Models\MoonshineUser;
use App\Models\Post;
use App\Models\PostSeries;
use App\Models\Question;
use App\Models\Recommendation;
use App\Models\Result;
use App\Models\Service;
use App\Models\StarGuest;
use App\Models\Tag;
use App\Models\User;
use App\Models\VideoReview;
use App\Models\Worker;
use App\MoonShine\Resources\Question\QuestionResource;
use App\Policies\MoonShine\CommentPolicy;
use App\Policies\MoonShine\FilialPolicy;
use App\Policies\MoonShine\LikePolicy;
use App\Policies\MoonShine\MentionPolicy;
use App\Policies\MoonShine\MoonshineUserPolicy;
use App\Policies\MoonShine\PostPolicy;
use App\Policies\MoonShine\PostSeriesPolicy;
use App\Policies\MoonShine\QuestionPolicy;
use App\Policies\MoonShine\RecommendationPolicy;
use App\Policies\MoonShine\ResultPolicy;
use App\Policies\MoonShine\ServicePolicy;
use App\Policies\MoonShine\StarGuestPolicy;
use App\Policies\MoonShine\TagPolicy;
use App\Policies\MoonShine\UserPolicy;
use App\Policies\MoonShine\VideoReviewPolicy;
use App\Policies\MoonShine\WorkerPolicy;
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
use App\MoonShine\Resources\VideoReview\VideoReviewResource;
use App\MoonShine\Resources\Post\PostResource;
use App\MoonShine\Resources\PostSeries\PostSeriesResource;
use App\MoonShine\Resources\Recomendation\RecommendationResource;
use App\MoonShine\Resources\Mention\MentionResource;
use App\MoonShine\Resources\Worker\WorkerResource;
use App\MoonShine\Resources\StarGuest\StarGuestResource;

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
                VideoReviewResource::class,
                PostResource::class,
                PostSeriesResource::class,
                RecommendationResource::class,
                MentionResource::class,
                WorkerResource::class,
                StarGuestResource::class,
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
            Gate::policy(Filial::class, FilialPolicy::class);
            Gate::policy(VideoReview::class, VideoReviewPolicy::class);
            Gate::policy(Post::class, PostPolicy::class);
            Gate::policy(PostSeries::class, PostSeriesPolicy::class);
            Gate::policy(Recommendation::class, RecommendationPolicy::class);
            Gate::policy(Mention::class, MentionPolicy::class);
            Gate::policy(Worker::class, WorkerPolicy::class);
            Gate::policy(StarGuest::class, StarGuestPolicy::class);
        }
    }
}
