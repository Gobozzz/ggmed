<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\ColorManager\Palettes\GrayPalette;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\Result\ResultResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\Service\ServiceResource;

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
            ...parent::menu(),
            MenuItem::make(UserResource::class, 'Пользователи')->icon('users'),
            MenuItem::make(ResultResource::class, 'Результаты')->icon('rectangle-stack'),
            MenuItem::make(QuestionResource::class, 'Вопросы')->icon('question-mark-circle'),
            MenuItem::make(ServiceResource::class, 'Услуги')->icon('currency-dollar'),
            MenuItem::make(TagResource::class, 'Теги')->icon('hashtag'),
            MenuItem::make(CommentResource::class, 'Комментарии')->icon('chat-bubble-left-right'),
            MenuItem::make(LikeResource::class, 'Лайки')->icon('heart'),
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
