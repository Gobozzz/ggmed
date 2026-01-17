<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Comment\Pages;

use App\Models\Result;
use App\MoonShine\Resources\Result\ResultResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use App\MoonShine\Resources\Comment\CommentResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends IndexPage<CommentResource>
 */
class CommentIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            MorphTo::make('К чему', 'commentable')
                ->types($this->getResource()->morphTypes)
                ->link(
                    link: fn(string $value, MorphTo $ctx) => app($this->getResource()->morphResources[$ctx->getTypeValue()])->getDetailPageUrl($ctx->getValue()),
                    name: fn(string $value) => $value,
                    blank: true,
                ),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Textarea::make('Текст', 'content', fn($item) => mb_substr($item->content, 0, 100, 'utf-8')),
            Date::make('Дата', 'created_at')
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
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class)->asyncSearch()
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

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return parent::modifyCreateButton($button)->canSee(fn() => false);
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
