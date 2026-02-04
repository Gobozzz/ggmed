<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\Enums\UserStatus;
use App\MoonShine\Resources\User\UserResource;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends IndexPage<UserResource>
 */
class UserIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Image::make('Аватар', 'avatar'),
            Text::make('Имя', 'name'),
            Phone::make('Телефон', 'phone'),
            Email::make('Почта', 'email'),
            Date::make('Дата регистрации', 'created_at'),
            Text::make('Статус', 'status', fn(Model $model) => $model->status->label()),
        ];
    }

    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return $button->canSee(fn() => false);
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
        $statuses = [];
        foreach (UserStatus::cases() as $status) {
            $statuses[$status->value] = $status->label();
        }

        return [
            Text::make('ID', 'id'),
            Text::make('Имя', 'name'),
            Text::make('Телефон', 'phone'),
            Text::make('Почта', 'email'),
            Select::make('Статус', 'status')->options($statuses),
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
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
