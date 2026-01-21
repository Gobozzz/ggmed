<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\Question;
use MoonShine\ColorManager\Palettes\GrayPalette;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\Result\ResultResource;
use MoonShine\Laravel\Resources\MoonShineUserResource;
use MoonShine\Laravel\Resources\MoonShineUserRoleResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\VideoReview\VideoReviewResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = GrayPalette::class;

    protected function getFooterMenu(): array
    {
        return [
            'https://ggmed.ru' => 'Перейти на сайт',
        ];
    }

    protected function getFooterCopyright(): string
    {
        return 'Product by Mates';
    }

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(MoonShineUserResource::class),
                MenuItem::make(MoonShineUserRoleResource::class),
            ])->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(UserResource::class, 'Пользователи')->icon('users')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(ResultResource::class, 'Результаты')->icon('rectangle-stack')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(QuestionResource::class, 'Вопросы')->icon('question-mark-circle')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(ServiceResource::class, 'Услуги')->icon('currency-dollar')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(FilialResource::class, 'Филиалы')->icon('building-office'),
            MenuItem::make(VideoReviewResource::class, 'Видео отзывы')->icon('video-camera'),
            MenuItem::make(TagResource::class, 'Теги')->icon('hashtag')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(CommentResource::class, 'Комментарии')->icon('chat-bubble-left-right')
                ->canSee(fn() => auth()->user()->isSuperUser()),
            MenuItem::make(LikeResource::class, 'Лайки')->icon('heart')
                ->canSee(fn() => auth()->user()->isSuperUser()),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
