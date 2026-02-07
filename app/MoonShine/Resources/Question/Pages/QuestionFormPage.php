<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Question\Pages;

use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Tag\TagResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<QuestionResource>
 */
class QuestionFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Вопрос', [
                        ID::make(),
                        Textarea::make('Вопрос (не более 500 символов)', 'title'),
                        Switcher::make('Опубликован?', 'is_published'),
                        Switcher::make('Горячий?', 'is_hot'),
                        CustomImage::make('Фото от пользователя', 'images')
                            ->removable()
                            ->multiple()
                            ->scaleDown(width: 800)
                            ->quality(80)
                            ->customName(fn (UploadedFile $file, Field $field) => 'questions/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->searchable()->creatable(),
                        Date::make('Дата', 'created_at'),
                    ]),
                    Tab::make('Ответ', [
                        EditorJs::make('Ответ', 'answer')->onApply(function ($item, $value) {
                            try {
                                if (count(json_decode($value, true)['blocks']) === 0) {
                                    $value = null;
                                }
                            } catch (Throwable $e) {
                                $value = null;
                            }
                            $item->answer = $value;

                            return $item;
                        }),
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
            'title' => ['required', 'string', 'max:500'],
            'answer' => ['nullable'],
            'is_hot' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
            'tags' => ['nullable', 'array', 'max:3'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpeg,jpg', 'max:4000'],
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
