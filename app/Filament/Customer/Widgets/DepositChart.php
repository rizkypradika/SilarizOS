<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Deposit;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DepositChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Saldo Deposit';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = Deposit::where('user_id', auth()->id())
            ->where('status', 'approved');

        if ($activeFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($activeFilter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($activeFilter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($activeFilter === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $data = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(balance_received) as total')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $labels = $data->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('d M'))->toArray();
        $totals = $data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Deposit',
                    'data' => $totals,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
