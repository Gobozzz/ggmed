<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Worker\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Worker\WorkerResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends FormPage<WorkerResource>
 */
class WorkerFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Image::make('Фото', 'image')
                    ->customName(fn(UploadedFile $file, Field $field) => "workers/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                Text::make('Фамилия', 'surname')->unescape(),
                Text::make('Имя', 'name')->unescape(),
                Text::make('Отчество', 'patronymic')->unescape(),
                Text::make('Должность', 'post')->unescape(),
                BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)
                    ->searchable()
                    ->nullable(fn() => auth()->user()->isSuperUser())
                    ->valuesQuery(static fn(Builder $q) => $q->when(auth()->user()->isFilialManagerUser(), fn(Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()))
                        ->select(['id', 'name'])),
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
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            "surname" => ['required', 'string', 'max:255'],
            "name" => ['required', 'string', 'max:255'],
            "patronymic" => ['nullable', 'string', 'max:255'],
            "post" => ['required', 'string', 'max:255'],
            "filial_id" => ['nullable', 'integer', 'exists:filials,id'],
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
