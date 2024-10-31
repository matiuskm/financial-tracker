<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use NumberFormatter;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) : null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) : now();

        $fmt = numfmt_create('id_ID', NumberFormatter::CURRENCY);
        $income = Transaction::incomes()
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->sum('amount');
        $expense = Transaction::expenses()
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->sum('amount');

        return [
            Stat::make('Total Income', numfmt_format_currency($fmt, $income, "Rp ")),
            Stat::make('Total Expense', numfmt_format_currency($fmt, $expense, "Rp ")),
            Stat::make('Balance', numfmt_format_currency($fmt, $income - $expense, "Rp ")),
        ];
    }
}
