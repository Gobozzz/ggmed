<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Recomendation\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Recomendation\RecommendationResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<RecommendationResource>
 */
class RecommendationFormPage extends FormPage
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
                        Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAll()),
                        CustomImage::make('Фото (горизонтальное)', 'image')
                            ->scaleDown(width: 1200)
                            ->quality(80)
                            ->customName(fn (UploadedFile $file, Field $field) => 'recomendations/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        Text::make('Заголовок (до 100 символов)', 'title')->unescape(),
                        Textarea::make('Описание (до 255 символов)', 'description')->unescape(),
                    ]),
                    Tab::make('SEO', [
                        Slug::make('Слаг', 'slug')->from('title')->unescape(),
                        Text::make('Meta заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta описание', 'meta_description')->unescape(),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Контент', 'content'),
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

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'level_hipe' => ['required', new Enum(LevelHipe::class)],
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            'title' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'content' => ['required'],
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
