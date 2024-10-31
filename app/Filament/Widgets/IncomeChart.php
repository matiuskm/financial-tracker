<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomeChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Income';

    protected static ?int $sort = 3;
    protected static string $color = 'success';

    protected function getData(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) : null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) : now();

        $data = Trend::query(Transaction::incomes())
            ->dateColumn('trx_date')
            ->between(
                start: $startDate,
                end: $endDate->endOfDay()
            )
            ->perDay()
            ->sum('amount');


        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'color' => 'success'
                ]
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date)
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
