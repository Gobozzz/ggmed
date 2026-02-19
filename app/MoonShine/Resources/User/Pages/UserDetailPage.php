<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Transaction\TransactionResource;
use App\MoonShine\Resources\User\UserResource;
use App\Services\BalanceService\BalanceServiceContract;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\LineBreak;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends DetailPage<UserResource>
 */
class UserDetailPage extends DetailPage
{
    public function __construct(CoreContract $core, private readonly BalanceServiceContract $balanceService)
    {
        parent::__construct($core);
    }

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Image::make('Аватар', 'avatar'),
            Text::make('Имя', 'name'),
            Phone::make('Телефон', 'phone'),
            Email::make('Почта', 'email'),
            Text::make('Статус', 'status', fn (Model $model) => $model->status->label()),
            Date::make('Дата регистрации', 'created_at'),
            Text::make('Баланс, GG COIN', 'balance', formatted: fn () => (string) $this->balanceService->getUserBalanceCached($this->getItem()->getKey())),
            HasMany::make('Вопросы', 'questions', resource: QuestionResource::class)->tabMode(),
            HasMany::make('Лайки', 'likes', resource: LikeResource::class)->tabMode(),
            HasMany::make('Комментарии', 'comments', resource: CommentResource::class)->tabMode(),
            HasMany::make('Транзакции', 'transactions', resource: TransactionResource::class)->tabMode(),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
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
            ActionButton::make('Начислить GG COIN')
                ->inModal(
                    title: 'Начисление GG COIN',
                    content: 'Укажите сумму и комментарий',
                    name: 'admin-replenished-modal',
                    builder: fn (Modal $modal, ActionButton $ctx) => $modal,
                    components: [
                        FormBuilder::make(fields: [
                            Number::make('Сумма', 'amount')->step(0.01),
                            Textarea::make('Комментарий (необяз, макс. 500 символов)', 'description'),
                        ],
                        )->name('admin-replenished-form')
                            ->async(
                                url: route('admin.transactions.replenished', $this->getItem()->getKey()),
                                events: [AlpineJs::event(JsEvent::FORM_RESET, 'admin-replenished-form')]
                            ),
                    ],
                ),
            ActionButton::make('Списать GG COIN')
                ->inModal(
                    title: 'Списание GG COIN',
                    content: 'Укажите сумму и комментарий',
                    name: 'admin-write-off-modal',
                    builder: fn (Modal $modal, ActionButton $ctx) => $modal,
                    components: [
                        FormBuilder::make(fields: [
                            Number::make('Сумма', 'amount')->step(0.01),
                            Textarea::make('Комментарий (необяз, макс. 500 символов)', 'description'),
                        ],
                        )->name('admin-write-off-form')
                            ->async(
                                url: route('admin.transactions.write-off', $this->getItem()->getKey()),
                                events: [AlpineJs::event(JsEvent::FORM_RESET, 'admin-write-off-form')]
                            ),
                    ],
                ),
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
