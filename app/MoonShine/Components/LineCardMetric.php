<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Components\MoonShineComponent;

/**
 * @method static static make(string $model, string $title, string $icon)
 */
final class LineCardMetric extends MoonShineComponent
{
    protected string $view = 'admin.components.line-card-metric';

    /**
     * @var class-string<Model>
     */
    protected string $model;

    protected string $title;

    protected string $icon;

    public function __construct(string $model, string $title, string $icon)
    {
        $this->model = $model;
        $this->title = $title;
        $this->icon = $icon;
        parent::__construct();
    }

    /*
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        $change = $this->getNewEntitiesThisMonth();
        $values = [0, $change];
        if ($change === 0) {
            $values = [1, 1];
        }
        $color = $change === 0 ? "#454545" : "#00ff0d";
        return [
            'value' => $this->model::query()->count(),
            'values' => $values,
            'change' => $change,
            'color' => $color,
            'title' => $this->title,
            'icon' => $this->icon,
        ];
    }

    protected function getNewEntitiesThisMonth(): int
    {
        $today = Carbon::today()->endOfDay();
        $startOfMonth = Carbon::today()->startOfMonth();

        return $this->model::whereBetween('created_at', [$startOfMonth, $today])
            ->count();
    }

}
