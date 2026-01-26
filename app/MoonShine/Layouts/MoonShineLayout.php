<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\Announcement\AnnouncementResource;
use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Document\DocumentResource;
use App\MoonShine\Resources\DocumentCategory\DocumentCategoryResource;
use App\MoonShine\Resources\Fact\FactResource;
use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Mention\MentionResource;
use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use App\MoonShine\Resources\Post\PostResource;
use App\MoonShine\Resources\PostSeries\PostSeriesResource;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Raffle\RaffleResource;
use App\MoonShine\Resources\Recomendation\RecommendationResource;
use App\MoonShine\Resources\Result\ResultResource;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\StarGuest\StarGuestResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\Test\TestResource;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\Vacancy\VacancyResource;
use App\MoonShine\Resources\VideoReview\VideoReviewResource;
use App\MoonShine\Resources\Worker\WorkerResource;
use MoonShine\ColorManager\ColorManager;
use MoonShine\ColorManager\Palettes\GrayPalette;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\Laravel\Resources\MoonShineUserResource;
use MoonShine\Laravel\Resources\MoonShineUserRoleResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;

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
            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
                MenuItem::make(MoonShineUserResource::class),
                MenuItem::make(MoonShineUserRoleResource::class),
            ])->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(UserResource::class, 'Пользователи')->icon('users')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(ResultResource::class, 'Результаты')->icon('rectangle-stack')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(QuestionResource::class, 'Вопросы')->icon('question-mark-circle')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(ServiceResource::class, 'Услуги')->icon('currency-dollar')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(FilialResource::class, 'Филиалы')->icon('building-office')
                ->canSee(fn () => auth()->user()->isSuperUser() || auth()->user()->isFilialManagerUser()),
            MenuItem::make(VideoReviewResource::class, 'Видео отзывы')->icon('video-camera')
                ->canSee(fn () => auth()->user()->isSuperUser() || auth()->user()->isFilialManagerUser()),
            MenuGroup::make('Блог', [
                MenuItem::make(PostResource::class, 'Посты')->icon('pencil'),
                MenuItem::make(PostSeriesResource::class, 'Серии')->icon('rectangle-stack')
                    ->canSee(fn () => auth()->user()->isSuperUser()),
            ])->icon('book-open'),
            MenuItem::make(RecommendationResource::class, 'Рекомендации')->icon('hand-thumb-up')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(WorkerResource::class, 'Работники')->icon('users')
                ->canSee(fn () => auth()->user()->isSuperUser() || auth()->user()->isFilialManagerUser()),
            MenuItem::make(MentionResource::class, 'Упоминания')->icon('link')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(StarGuestResource::class, 'Звездные гости')->icon('star')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuGroup::make('Документы, бумажки', [
                MenuItem::make(DocumentCategoryResource::class, 'Категории')->icon('rectangle-stack'),
                MenuItem::make(DocumentResource::class, 'Документ')->icon('document'),
            ])->icon('paper-clip')->canSee(fn () => auth()->user()->isSuperUser()),
            MenuGroup::make('Магазин', [
                MenuItem::make(ProductResource::class, 'Товары')->icon('shopping-bag'),
                MenuItem::make(OrderResource::class, 'Заказы')->icon('archive-box'),
                MenuItem::make(OrderItemResource::class, 'Заказаные позиции')->canSee(fn () => false),
            ])->icon('shopping-cart')->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(VacancyResource::class, 'Вакансии')->icon('briefcase')
                ->canSee(fn () => auth()->user()->isSuperUser() || auth()->user()->isFilialManagerUser()),
            MenuItem::make(FactResource::class, 'Факты')->icon('ellipsis-horizontal-circle')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(AnnouncementResource::class, 'Анонсы')->icon('fire')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(TestResource::class, 'Тесты')->icon('academic-cap')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(RaffleResource::class, 'Розыгрыши')->icon('gift')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(TagResource::class, 'Теги')->icon('hashtag')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(CommentResource::class, 'Комментарии')->icon('chat-bubble-left-right')
                ->canSee(fn () => auth()->user()->isSuperUser()),
            MenuItem::make(LikeResource::class, 'Лайки')->icon('heart')
                ->canSee(fn () => auth()->user()->isSuperUser()),
        ];
    }

    /**
     * @param  ColorManager  $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
