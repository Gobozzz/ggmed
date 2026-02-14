<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Raffle\Pages;

use App\Enums\RaffleType;
use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Raffle\RaffleResource;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\LineBreak;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<RaffleResource>
 */
class RaffleFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                ActionButton::make('Сохранить')->primary()->setAttribute('type', 'submit'),
                Tabs::make([
                    Tab::make('Основная информация', [
                        CustomImage::make('Фото (горизонтальное, необяз)', 'image')
                            ->scaleDown(width: 320)
                            ->quality(70)
                            ->removable()
                            ->customName(fn (UploadedFile $file, Field $field) => 'raffles/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        File::make('Запись розыгрыша (необяз, не более 20мб, горизонтальное)', 'video')
                            ->removable()
                            ->customName(fn (UploadedFile $file, Field $field) => 'raffles-videos/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        Text::make('Заголовок', 'title'),
                        Textarea::make('Описание', 'description'),
                        Date::make('Дата конца', 'date_end'),
                        BelongsTo::make('Победитель', 'winner', formatted: fn ($item) => $item->name.' ('.($item->phone ?? $item->email).')', resource: UserResource::class)
                            ->nullable()
                            ->asyncSearch(),
                    ]),
                    Tab::make('SEO', [
                        Text::make('Meta Заголовок', 'meta_title'),
                        Textarea::make('Meta Описание', 'meta_description'),
                    ]),
                    Tab::make('Редактор(обязательно, Описание условий)', [
                        EditorJs::make('Контент', 'content')->onApply(function ($item, $value) {
                            try {
                                if (count(json_decode($value, true)['blocks']) === 0) {
                                    $value = null;
                                }
                            } catch (Throwable $e) {
                                $value = null;
                            }
                            $item->content = $value;

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

    public function prepareForValidation(): void
    {
        if ($this->getItem() !== null) {
            request()->merge([
                'type' => $this->getItem()->type->value,
            ]);
        } else {
            request()->merge([
                'type' => RaffleType::MANUAL->value,
            ]);
        }
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'type' => ['required', new Enum(RaffleType::class)],
            'image' => ['nullable', 'image', 'max:1024'],
            'video' => ['nullable', 'file', 'mimes:mp4', 'max:22000'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'date_end' => ['required', 'date'],
            'winner_id' => ['nullable', 'integer', 'exists:users,id'],
            'content' => ['nullable'],
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
            ActionButton::make('Оповестить в ТГ канале о розыгрыше', route('admin.raffles.send-messenger-channel', $this->getItem()?->id ?? 1))
                ->icon('bell-alert')
                ->primary()
                ->async()
                ->canSee(fn () => $this->getItem()),
            LineBreak::make(),
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
