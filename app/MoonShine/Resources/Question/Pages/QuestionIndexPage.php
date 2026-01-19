<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Question\Pages;

use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use App\MoonShine\Resources\Question\QuestionResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends IndexPage<QuestionResource>
 */
class QuestionIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Комменты', 'comments', fn($item) => (string)$item->comments->count() > 0 ? $item->comments->count() : "Нет")->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: "chat-bubble-left-right",
            ),
            Text::make('Лайки', 'likes', fn($item) => $item->likes->count() > 0 ? $item->likes->count() : "Нет")->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: "heart",
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Textarea::make('Вопрос', 'title', fn($item) => mb_substr($item->title, 0, 100, 'utf-8')),
            Textarea::make('Ответ', 'answer', fn($item) => $item->answer ? "Ответ есть" : "Не дан"),
            Switcher::make('Горячий?', 'is_hot'),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Date::make('Дата', 'created_at'),
        ];
    }

    /**
     * @return ListOf<ActionButtonContract>
     */
    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class)->asyncSearch(),
            Switcher::make('Горячий?', 'is_hot'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * @param TableBuilder $component
     *
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
