<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Post\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\PostSeries\PostSeriesResource;
use App\MoonShine\Resources\Tag\TagResource;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Post\PostResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<PostResource>
 */
class PostFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Tabs::make([
                    Tab::make('Основная информация', [
                        Image::make('Фото (рек. горизонтальное)', 'image')
                            ->customName(fn(UploadedFile $file, Field $field) => "posts/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                        Text::make('Заголовок', 'title')->unescape(),
                        Textarea::make('Описание', 'description')->unescape(),
                        Number::make('Время на чтение, мин.', 'time_to_read', fn($item) => $item->time_to_read . " мин."),
                        Slug::make('Слаг (необязательно)', 'slug')->from('title'),
                        Text::make('Meta заголовок (необязательно)', 'meta_title')->unescape(),
                        Textarea::make('Meta описание (необязательно)', 'meta_description')->unescape(),
                        BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)
                            ->nullable(fn() => auth()->user()->isSuperUser() || auth()->user()->isAuthorPostsUser())
                            ->searchable()
                            ->valuesQuery(static fn(Builder $q) => $q->when(auth()->user()->isFilialManagerUser(), fn(Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()))
                                ->select(['id', 'name'])),
                        MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->searchable()->creatable(),
                        BelongsTo::make('Автор', 'author', formatted: fn($item) => $item->name . " (" . $item->moonshineUserRole->name . ")", resource: MoonShineUserResource::class)->nullable()
                            ->canSee(fn() => $this->getItem() !== null && auth()->user()->isSuperUser()),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Контент', 'content'),
                    ]),
                    Tab::make('Серии ' . ($this->getItem()?->series->count() ?? ""), [
                        BelongsToMany::make('Серии', 'series', resource: PostSeriesResource::class),
                    ]),
                ]),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    public function prepareForValidation(): void
    {
        if ($this->getItem() === null) {
            request()->merge([
                'author_id' => auth()->user()->getKey(),
            ]);
        } else if (!auth()->user()->isSuperUser()) {
            request()->merge([
                'author_id' => $this->getItem()->author_id,
            ]);
        }
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug' . ($item->getKey() ? "," . $item->getKey() : "")],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'time_to_read' => ['required', 'numeric', 'min:1', 'max:200'],
            'content' => ['required'],
            'filial_id' => ['nullable', 'integer', 'exists:filials,id'],
            'tags' => ['nullable', 'array', 'max:3'],
        ];
    }

    /**
     * @param FormBuilder $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
