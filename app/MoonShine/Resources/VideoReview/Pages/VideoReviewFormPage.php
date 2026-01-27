<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\VideoReview\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\VideoReview\VideoReviewResource;
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
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends FormPage<VideoReviewResource>
 */
class VideoReviewFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAllLevelsHipe()),
                Image::make('Превью (не более 1мб, вертикальное)', 'preview')
                    ->customName(fn (UploadedFile $file, Field $field) => 'video-reviews-previews/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                File::make('Видео (не более 20мб, вертикальное)', 'video')
                    ->customName(fn (UploadedFile $file, Field $field) => 'video-reviews/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                Image::make('Фотки ДО (вертикальные, необяз)', 'images_before')
                    ->customName(fn (UploadedFile $file, Field $field) => 'video-reviews-before/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension())
                    ->multiple()
                    ->removable(),
                Textarea::make('Описание', 'content', fn ($item) => mb_substr($item->content, 0, 100, 'utf-8')),
                BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)
                    ->nullable(fn () => auth()->user()->isSuperUser())
                    ->valuesQuery(static fn (Builder $q) => $q->when(auth()->user()->isFilialManagerUser(), fn (Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()))
                        ->select(['id', 'name'])),
                MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->searchable()->creatable(),
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
            'images_before' => ['nullable', 'array', 'min:1'],
            'images_before.*' => ['image', 'max:1024'],
            'preview' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            'video' => [$item->getKey() === null ? 'required' : 'nullable', 'file', 'mimes:mp4', 'max:22000'],
            'content' => ['nullable', 'string', 'max:500'],
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
