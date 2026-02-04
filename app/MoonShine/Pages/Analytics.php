<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Comment;
use App\Models\Filial;
use App\Models\Like;
use App\Models\Mention;
use App\Models\Order;
use App\Models\Post;
use App\Models\Question;
use App\Models\Raffle;
use App\Models\Result;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VideoReview;
use App\Models\Worker;
use Carbon\Carbon;
use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Apexcharts\Support\SeriesItem;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;

class Analytics extends Page
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
        return $this->title ?: 'Аналитика';
    }

    protected function prepareBeforeRender(): void
    {
        parent::prepareBeforeRender();

        if (!auth()->user()->isSuperUser()) {
            abort(403);
        }
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $coinStats = $this->getCoinStats();
        $commentStats = $this->getCommentsStats();
        $likeStats = $this->getLikesStats();

        return [
            Grid::make([
                Column::make([
                    Grid::make([
                        Column::make([
                            Tabs::make([
                                Tab::make('За 6 месяцев', [
                                    LineChartMetric::make('Активность пользователей')
                                        ->icon('arrow-trending-up')
                                        ->series([
                                            SeriesItem::make('Кол-во транзакций GG COIN', $coinStats['months'])->color('#e6ff00'),
                                            SeriesItem::make('Кол-во комментариев', $commentStats['months'])->color('#0011ff'),
                                            SeriesItem::make('Кол-во лайков', $likeStats['months'])->color('#ff0000'),
                                        ]),
                                ]),
                                Tab::make('За 30 дней', [
                                    LineChartMetric::make('Активность пользователей')
                                        ->icon('arrow-trending-up')
                                        ->series([
                                            SeriesItem::make('Кол-во транзакций GG COIN', $coinStats['days'])->color('#e6ff00'),
                                            SeriesItem::make('Кол-во комментариев', $commentStats['days'])->color('#0011ff'),
                                            SeriesItem::make('Кол-во лайков', $likeStats['days'])->color('#ff0000'),
                                        ]),
                                ]),
                            ]),
                        ]),
                        ValueMetric::make('Активных розыгрышей')
                            ->value(fn() => Raffle::query()->whereNull('winner_id')->count())->columnSpan(6, 6),
                        ValueMetric::make('Пользователей')
                            ->value(fn() => User::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Сделано заказов')
                            ->value(fn() => Order::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Результаты')
                            ->value(fn() => Result::query()->count() + VideoReview::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Статей')
                            ->value(fn() => Post::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Филиалы')
                            ->value(fn() => Filial::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Работники')
                            ->value(fn() => Worker::query()->count())->columnSpan(6, 6),
                        ValueMetric::make('Упоминания')
                            ->value(fn() => Mention::query()->count())->columnSpan(6, 6),
                    ]),
                ], 6),
                Column::make([
                    Grid::make([
                        DonutChartMetric::make('Вопросы на форуме')
                            ->values([
                                'Отвеченные' => Question::query()->whereNotNull('answer')->count(),
                                'Неотвеченные' => Question::query()->whereNull('answer')->count(),
                            ])->columnSpan(12, 6),
                        DonutChartMetric::make('Персонажи GG GAME')
                            ->values([
                                'Ур. 1' => 14,
                                'Ур. 2' => 6,
                                'Ур. 3' => 5,
                                'Ур. 4' => 3,
                                'Ур. 5' => 2,
                            ])->columnSpan(12, 6),
                    ]),
                ], 6, 12),
            ]),
        ];
    }

    private function getLikesStats(): array
    {
        $countMonths = 6;

        $startMonth = Carbon::now()->subMonths($countMonths - 1)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = Like::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('period')
            ->get();

        $seriesMonths = [];
        $current = $startMonth->copy();

        for ($i = 0; $i < $countMonths; $i++) {
            $periodStart = $current->copy()->startOfMonth();
            $periodEnd = $current->copy()->endOfMonth();

            $countTransactionsByMonth = $result->whereBetween('period', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])->sum('count');

            $seriesMonths[$periodStart->startOfMonth()->format('Y.m.d')] = $countTransactionsByMonth;

            $current->addMonth();
        }

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $result = Like::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get();

        $seriesDays = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y.m.d');

            $value = $result->firstWhere('period', $date->format('Y-m-d'));
            $count = $value ? $value->count : 0;

            $seriesDays[$dateKey] = $count;
        }

        return [
            'months' => $seriesMonths,
            'days' => $seriesDays,
        ];
    }

    private function getCommentsStats(): array
    {
        $countMonths = 6;

        $startMonth = Carbon::now()->subMonths($countMonths - 1)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $result = Comment::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('period')
            ->get();

        $seriesMonths = [];
        $current = $startMonth->copy();

        for ($i = 0; $i < $countMonths; $i++) {
            $periodStart = $current->copy()->startOfMonth();
            $periodEnd = $current->copy()->endOfMonth();

            $countTransactionsByMonth = $result->whereBetween('period', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])->sum('count');

            $seriesMonths[$periodStart->startOfMonth()->format('Y.m.d')] = $countTransactionsByMonth;

            $current->addMonth();
        }

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $result = Comment::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get();

        $seriesDays = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y.m.d');

            $value = $result->firstWhere('period', $date->format('Y-m-d'));
            $count = $value ? $value->count : 0;

            $seriesDays[$dateKey] = $count;
        }

        return [
            'months' => $seriesMonths,
            'days' => $seriesDays,
        ];
    }

    private function getCoinStats(): array
    {
        $countMonths = 6;

        $startMonth = Carbon::now()->subMonths($countMonths - 1)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth(); // или сегодня

        $result = Transaction::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('period')
            ->get();

        $seriesMonths = [];
        $current = $startMonth->copy();

        for ($i = 0; $i < $countMonths; $i++) {
            $periodStart = $current->copy()->startOfMonth();
            $periodEnd = $current->copy()->endOfMonth();

            $countTransactionsByMonth = $result->whereBetween('period', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])->sum('count');

            $seriesMonths[$periodStart->startOfMonth()->format('Y.m.d')] = $countTransactionsByMonth;

            $current->addMonth();
        }

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $result = Transaction::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get();

        $seriesDays = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y.m.d');

            $value = $result->firstWhere('period', $date->format('Y-m-d'));
            $count = $value ? $value->count : 0;

            $seriesDays[$dateKey] = $count;
        }

        return [
            'months' => $seriesMonths,
            'days' => $seriesDays,
        ];
    }
}
