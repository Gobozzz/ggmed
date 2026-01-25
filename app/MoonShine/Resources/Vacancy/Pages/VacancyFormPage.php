<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Vacancy\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\Vacancy\VacancyResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\EasyMde\Fields\Markdown;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;
use Throwable;

/**
 * @extends FormPage<VacancyResource>
 */
class VacancyFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Заголовок', 'title')->unescape(),
                Text::make('ЗП (необяз)', 'salary')->unescape(),
                Select::make('Валюта', 'valute')->options(['₽' => '₽', '$' => '$']),
                Text::make('За что платите?', 'what_pay')->default('в месяц')->unescape(),
                Text::make('Ответственный', 'responsible')->placeholder('Иван Иванов, +77777777777')->unescape(),
                Text::make('Адрес или удаленно', 'address')->unescape(),
                Url::make('Ссылка на внешний ист.(необяз)', 'url')->placeholder('hh.ru')->unescape(),
                Markdown::make('Описание(необяз)', 'content')->unescape(),
                BelongsTo::make('Автор', 'author', resource: MoonShineUserResource::class)
                    ->nullable(fn () => auth()->user()->isSuperUser() && $this->getItem() !== null)
                    ->canSee(fn () => auth()->user()->isSuperUser() && $this->getItem() !== null),
                BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)
                    ->valuesQuery(static fn (Builder $q) => $q->when(auth()->user()->isFilialManagerUser(), fn (Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()))
                        ->select(['id', 'name']))
                    ->nullable(fn () => auth()->user()->isSuperUser()),
            ]),
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->getItem() === null) {
            request()->merge([
                'author_id' => auth()->user()->getKey(),
            ]);
        } elseif (! auth()->user()->isSuperUser()) {
            request()->merge([
                'author_id' => $this->getItem()->author_id,
            ]);
        }
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'salary' => ['nullable', 'string', 'max:50'],
            'valute' => ['required', 'string', 'max:10'],
            'what_pay' => ['required', 'string', 'max:50'],
            'responsible' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'author_id' => ['nullable', 'integer', 'exists:moonshine_users,id'],
            'filial_id' => ['nullable', 'integer', 'exists:filials,id'],
        ];
    }

    /**
     * @param  FormBuilder  $component
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
