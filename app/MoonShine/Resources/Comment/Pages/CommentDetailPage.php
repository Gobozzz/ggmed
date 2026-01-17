<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Comment\Pages;

use App\Models\Result;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Comment\CommentResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends DetailPage<CommentResource>
 */
class CommentDetailPage extends DetailPage
{
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
            Textarea::make('Текст', 'content'),
            Date::make('Дата', 'created_at')
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param TableBuilder $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
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
