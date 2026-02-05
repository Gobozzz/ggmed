<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Test\Pages;

use App\Enums\LevelHipe;
use App\Enums\TypeExercise;
use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Test\TestResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\EasyMde\Fields\Markdown;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends FormPage<TestResource>
 */
class TestFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        $types = [];
        foreach (TypeExercise::cases() as $type) {
            $types[$type->value] = $type->label();
        }

        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основные данные', [
                        ID::make(),
                        Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAllLevelsHipe()),
                        CustomImage::make('Фото (горизонтальное)', 'image')
                            ->scaleDown(width: 320)
                            ->quality(70)
                            ->customName(fn(UploadedFile $file, Field $field) => 'tests/' . Carbon::now()->format('Y-m') . '/' . Str::random(50) . '.' . $file->extension()),
                        Text::make('Название', 'title')->unescape(),
                        Textarea::make('Описание', 'description')->unescape(),
                        Text::make('Meta заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta описание', 'meta_description')->unescape(),
                    ]),
                    Tab::make('Упражнения', [
                        Json::make('Упражнения', 'exercises')
                            ->fields([
                                Select::make('Тип', 'type')->options($types),
                                Text::make('Заголовок', 'title')->unescape(),
                                Markdown::make('Описание', 'description')->unescape(),
                                Json::make('Ответы', 'answers')
                                    ->fields([
                                        Text::make('Вариант', 'title')->unescape(),
                                        Switcher::make('Верный?', 'trusty'),
                                    ])
                                    ->removable(),
                            ])
                            ->removable()
                            ->vertical(),
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
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'exercises' => ['required', 'array', 'min:2'],
        ];
    }

    /**
     * @param FormBuilder $component
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
