<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Post\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\Post\PostResource;
use App\MoonShine\Resources\PostSeries\PostSeriesResource;
use App\MoonShine\Resources\Tag\TagResource;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
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
                ActionButton::make('Сохранить')->primary()->setAttribute('type', 'submit'),
                ID::make(),
                Tabs::make([
                    Tab::make('Основная информация', [
                        Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAll())->canSee(fn () => auth()->user()->isSuperUser()),
                        Switcher::make('Опубликовать?', 'is_published'),
                        CustomImage::make('Фото (горизонтальное)', 'image')
                            ->scaleDown(width: 1200)
                            ->quality(80)
                            ->customName(fn (UploadedFile $file, Field $field) => 'posts/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        Text::make('Заголовок (до 100 символов)', 'title')->unescape(),
                        Textarea::make('Описание (до 255 символов)', 'description')->unescape(),
                        Number::make('Время на чтение, мин.', 'time_to_read', fn ($item) => $item->time_to_read.' мин.'),
                        MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->searchable()->creatable(),
                        BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)
                            ->nullable(fn () => auth()->user()->isSuperUser() || auth()->user()->isAuthorPostsUser())
                            ->searchable()
                            ->valuesQuery(static fn (Builder $q) => $q->when(auth()->user()->isFilialManagerUser(), fn (Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()))
                                ->select(['id', 'name'])),
                        BelongsTo::make('Автор', 'author', formatted: fn ($item) => $item->name.' ('.$item->moonshineUserRole->name.')', resource: MoonShineUserResource::class)->nullable()
                            ->canSee(fn () => $this->getItem() !== null && auth()->user()->isSuperUser()),
                        Date::make('Дата публикации', 'created_at'),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Контент', 'content'),
                    ]),
                    Tab::make('SEO', [
                        Slug::make('Слаг (необязательно)', 'slug')->from('title'),
                        Text::make('Meta заголовок (необязательно)', 'meta_title')->unescape(),
                        Textarea::make('Meta описание (необязательно)', 'meta_description')->unescape(),
                    ]),
                    Tab::make('Серии '.($this->getItem()?->series->count() ?? ''), [
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
        } elseif (! auth()->user()->isSuperUser()) {
            request()->merge([
                'author_id' => $this->getItem()->author_id,
            ]);
        }
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'is_published' => ['required', 'boolean'],
            'level_hipe' => ['required', new Enum(LevelHipe::class)],
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:4000'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:services,slug'.($item->getKey() ? ','.$item->getKey() : '')],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'time_to_read' => ['nullable', 'numeric', 'min:1', 'max:200'],
            'content' => ['required'],
            'filial_id' => ['nullable', 'integer', 'exists:filials,id'],
            'tags' => ['nullable', 'array', 'max:3'],
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
