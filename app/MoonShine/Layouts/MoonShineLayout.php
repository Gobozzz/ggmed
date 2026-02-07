<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Document;
use App\Models\Fact;
use App\Models\Filial;
use App\Models\Like;
use App\Models\Mention;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostSeries;
use App\Models\Product;
use App\Models\Question;
use App\Models\Raffle;
use App\Models\Recommendation;
use App\Models\Result;
use App\Models\Service;
use App\Models\StarGuest;
use App\Models\Tag;
use App\Models\Test;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\VideoReview;
use App\Models\Worker;
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
use App\MoonShine\Resources\Page\PageResource;
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
use App\MoonShine\Resources\Transaction\TransactionResource;
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
                ->canSee(fn () => auth()->user()->can('view-any', User::class)),
            MenuItem::make(ResultResource::class, 'Результаты')->icon('rectangle-stack')
                ->canSee(fn () => auth()->user()->can('view-any', Result::class)),
            MenuItem::make(QuestionResource::class, 'Вопросы')->icon('question-mark-circle')
                ->canSee(fn () => auth()->user()->can('view-any', Question::class)),
            MenuItem::make(ServiceResource::class, 'Услуги')->icon('currency-dollar')
                ->canSee(fn () => auth()->user()->can('view-any', Service::class)),
            MenuItem::make(FilialResource::class, 'Филиалы')->icon('building-office')
                ->canSee(fn () => auth()->user()->can('view-any', Filial::class)),
            MenuItem::make(VideoReviewResource::class, 'Видео отзывы')->icon('video-camera')
                ->canSee(fn () => auth()->user()->can('view-any', VideoReview::class)),
            MenuGroup::make('Блог', [
                MenuItem::make(PostResource::class, 'Посты')->icon('pencil')
                    ->canSee(fn () => auth()->user()->can('view-any', Post::class)),
                MenuItem::make(PostSeriesResource::class, 'Серии')->icon('rectangle-stack')
                    ->canSee(fn () => auth()->user()->can('view-any', PostSeries::class)),
            ])->icon('book-open'),
            MenuItem::make(RecommendationResource::class, 'Рекомендации')->icon('hand-thumb-up')
                ->canSee(fn () => auth()->user()->can('view-any', Recommendation::class)),
            MenuItem::make(WorkerResource::class, 'Работники')->icon('users')
                ->canSee(fn () => auth()->user()->can('view-any', Worker::class)),
            MenuItem::make(MentionResource::class, 'Упоминания')->icon('link')
                ->canSee(fn () => auth()->user()->can('view-any', Mention::class)),
            MenuItem::make(StarGuestResource::class, 'Звездные гости')->icon('star')
                ->canSee(fn () => auth()->user()->can('view-any', StarGuest::class)),
            MenuGroup::make('Документы, бумажки', [
                MenuItem::make(DocumentCategoryResource::class, 'Категории')->icon('rectangle-stack'),
                MenuItem::make(DocumentResource::class, 'Документ')->icon('document'),
            ])->icon('paper-clip')->canSee(fn () => auth()->user()->can('view-any', Document::class)),
            MenuGroup::make('Магазин', [
                MenuItem::make(ProductResource::class, 'Товары')->icon('shopping-bag'),
                MenuItem::make(OrderResource::class, 'Заказы')->icon('archive-box'),
                MenuItem::make(OrderItemResource::class, 'Заказаные позиции')->canSee(fn () => false),
            ])->icon('shopping-cart')->canSee(fn () => auth()->user()->can('view-any', Product::class)),
            MenuGroup::make('GG COIN Игра', [
                MenuItem::make(TransactionResource::class, 'Транзакции')->icon('currency-dollar'),
            ])->icon('puzzle-piece')->canSee(fn () => auth()->user()->can('view-any', Transaction::class)),
            MenuItem::make(VacancyResource::class, 'Вакансии')->icon('briefcase')
                ->canSee(fn () => auth()->user()->can('view-any', Vacancy::class)),
            MenuItem::make(FactResource::class, 'Факты')->icon('ellipsis-horizontal-circle')
                ->canSee(fn () => auth()->user()->can('view-any', Fact::class)),
            MenuItem::make(AnnouncementResource::class, 'Анонсы')->icon('fire')
                ->canSee(fn () => auth()->user()->can('view-any', Announcement::class)),
            MenuItem::make(TestResource::class, 'Тесты')->icon('academic-cap')
                ->canSee(fn () => auth()->user()->can('view-any', Test::class)),
            MenuItem::make(RaffleResource::class, 'Розыгрыши')->icon('gift')
                ->canSee(fn () => auth()->user()->can('view-any', Raffle::class)),
            MenuItem::make(PageResource::class, 'Страницы')->icon('book-open')
                ->canSee(fn () => auth()->user()->can('view-any', Page::class)),
            MenuItem::make(TagResource::class, 'Теги')->icon('hashtag')
                ->canSee(fn () => auth()->user()->can('view-any', Tag::class)),
            MenuItem::make(CommentResource::class, 'Комментарии')->icon('chat-bubble-left-right')
                ->canSee(fn () => auth()->user()->can('view-any', Comment::class)),
            MenuItem::make(LikeResource::class, 'Лайки')->icon('heart')
                ->canSee(fn () => auth()->user()->can('view-any', Like::class)),
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
