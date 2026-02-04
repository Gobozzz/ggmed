@use(MoonShine\Apexcharts\Components\SparklineChartMetric)

@props([
    'value' => 0,
    'values' => [],
    'change' => 0,
    'color' => "#454545",
    'title' => "",
    'icon' => "",
])

<div>
    {!!
     SparklineChartMetric::make($title)
        ->withoutTooltip()
        ->icon($icon)
        ->values($values)
        ->value($value === 0 ? "Отсутствуют" : $value)
        ->change(value:$change, prefix: $change === 0 ? "" : "+ ")
        ->changeText("прибавили за этот месяц", "убавилось за этот месяц")
        ->colors([$color])
    !!}
</div>


