<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = true;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 8,
    ];

    protected ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('brightdor.dashboard.revenue_chart');
    }

    public function getDescription(): ?string
    {
        return __('brightdor.dashboard.revenue_chart_desc');
    }

    protected function getData(): array
    {
        $start = Carbon::now()->subMonths(5)->startOfMonth();

        $rows = Transaction::query()
            ->selectRaw("strftime('%Y-%m', COALESCE(paid_at, created_at)) as ym, SUM(amount) as total")
            ->where('status', 'success')
            ->where('type', 'payment')
            ->where(function ($q) use ($start) {
                $q->where('paid_at', '>=', $start)
                    ->orWhere(function ($q2) use ($start) {
                        $q2->whereNull('paid_at')->where('created_at', '>=', $start);
                    });
            })
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
            $data[] = (float) ($rows[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('brightdor.dashboard.revenue'),
                    'data' => $data,
                    'fill' => true,
                    'backgroundColor' => 'rgba(196, 165, 116, 0.14)',
                    'borderColor' => '#141414',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => '#141414',
                    'pointBorderColor' => '#ffffff',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'color' => 'rgba(20,20,20,0.05)',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'color' => '#8a8f98',
                        'font' => ['size' => 11],
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => 'rgba(20,20,20,0.05)',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'color' => '#8a8f98',
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
