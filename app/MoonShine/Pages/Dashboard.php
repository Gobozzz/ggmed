<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Adapters\AiAssistant\AiAssistantContract;
use App\Enums\RaffleType;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Question;
use App\Models\Raffle;
use App\Models\User;
use App\MoonShine\Components\LineCardMetric;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Raffle\RaffleResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use MoonShine\Apexcharts\Components\SparklineChartMetric;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\TypeCasts\ModelCaster;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Card;
use MoonShine\UI\Components\CardsBuilder;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Link;

#[\MoonShine\MenuManager\Attributes\SkipMenu]
class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: '';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $aiAssistant = app(AiAssistantContract::class);
        $remainsTokens = $aiAssistant->getRemainsTokens();
        $actual_raffles = Raffle::query()->where('type', RaffleType::MANUAL)->whereNull('winner_id')->orderBy('date_end')->paginate(3, ['id', 'title', 'description', 'image', 'date_end']);
        $actual_questions = Question::query()->whereNull('answer')->paginate(3, ['id', 'title', 'user_id']);

        return [
            Heading::make('Добро пожаловать в админ панель GGMED!', 1),
            Box::make([
                Flex::make([
                    Heading::make('В кратце наши делишки', 3),
                    Link::make(fn () => app(Analytics::class)->getUrl(), 'Больше аналитики')->icon('chart-bar')
                        ->canSee(fn () => auth()->user()->isSuperUser())
                        ->style(['background:#5500FF', 'padding:5px 10px', 'border-radius:4px', 'color:white']),
                ])->justifyAlign('between'),
                Box::make([
                    SparklineChartMetric::make(''),
                ])->setAttribute('class', 'hidden'),
                Grid::make([
                    Column::make([
                        LineCardMetric::make(User::class, 'Пользователи', 'user-plus'),
                    ])->columnSpan(4),
                    Column::make([
                        LineCardMetric::make(Like::class, 'Лайки', 'heart'),
                    ])->columnSpan(4),
                    Column::make([
                        LineCardMetric::make(Comment::class, 'Комментарии', 'chat-bubble-left-ellipsis'),
                    ])->columnSpan(4),
                ]),
            ]),
            Grid::make([
                Column::make([
                    Box::make([
                        Flex::make([
                            Heading::make('Не забудьте определить победителя розыгрыша', 3),
                            Link::make(fn () => app(RaffleResource::class)->getIndexPageUrl(), 'Розыгрыши')->icon('arrow-up-right')
                                ->canSee(fn () => auth()->user()->isSuperUser())
                                ->style(['background:#5500FF', 'padding:5px 10px', 'border-radius:4px', 'color:white']),
                        ])->justifyAlign('between'),
                        CardsBuilder::make()
                            ->items($actual_raffles)
                            ->cast(new ModelCaster(Raffle::class))
                            ->thumbnail(fn ($item) => $item->image ? Storage::url($item->image) : '/admin-files/gg.png')
                            ->header(fn ($item) => Badge::make('Вы должны его провести: '.$item->date_end->locale('ru')->isoFormat('D MMMM'), $this->getBadgeColorForRaffle($item->date_end)))
                            ->title('title')
                            ->url(fn ($item) => app(RaffleResource::class)->getDetailPageUrl($item->getKey()))
                            ->name('actual-raffles')
                            ->async(),
                    ]),
                ])->columnSpan(6),
                Column::make([
                    Box::make([
                        Flex::make([
                            Heading::make('Надо ответить людям на вопросы', 3),
                            Link::make(fn () => app(QuestionResource::class)->getIndexPageUrl(), 'Вопросы')->icon('arrow-up-right')
                                ->canSee(fn () => auth()->user()->isSuperUser())
                                ->style(['background:#5500FF', 'padding:5px 10px', 'border-radius:4px', 'color:white']),
                        ])->justifyAlign('between'),
                        CardsBuilder::make()
                            ->items($actual_questions)
                            ->cast(new ModelCaster(Question::class))
                            ->title(fn ($item) => mb_substr($item->title, 0, 100, 'utf8'))
                            ->url(fn ($item) => app(QuestionResource::class)->getFormPageUrl($item->getKey()))
                            ->name('actual-questions')
                            ->async(),
                    ]),
                    Grid::make([
                        Column::make([
                            Card::make(
                                thumbnail: '/admin-files/ai.jpg',
                                values: [
                                    'Остаток токенов' => Link::make($aiAssistant->getPayLink(), fn () => $remainsTokens ? (number_format($remainsTokens, 0, '', ' ').' токенов') : 'Нет информации')->icon('cpu-chip')
                                        ->style(['background:'.($remainsTokens === null || $remainsTokens <= 200000 ? '#ff0000' : ($remainsTokens <= 600000 ? '#ff6600' : '#178a00')), 'padding:5px 10px', 'border-radius:4px', 'color:white']),
                                ]
                            ),
                        ])->columnSpan(6),
                    ]),
                ])->columnSpan(6),
            ])->canSee(fn () => auth()->user()->isSuperUser()),
        ];
    }

    private function getBadgeColorForRaffle(Carbon $date_end): string
    {
        if ($date_end->endOfDay()->isPast()) {
            return 'error';
        }

        return 'success';
    }
}
