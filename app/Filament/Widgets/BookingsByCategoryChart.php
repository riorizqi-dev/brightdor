<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\VendorCategory;
use Filament\Widgets\ChartWidget;

class BookingsByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = true;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    protected ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('brightdor.dashboard.booking_chart');
    }

    public function getDescription(): ?string
    {
        return __('brightdor.dashboard.booking_chart_desc');
    }

    protected function getData(): array
    {
        $categories = VendorCategory::query()
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $counts = Booking::query()
            ->join('vendors', 'bookings.vendor_id', '=', 'vendors.id')
            ->selectRaw('vendors.vendor_category_id as category_id, COUNT(*) as total')
            ->groupBy('vendors.vendor_category_id')
            ->pluck('total', 'category_id');

        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $labels[] = $category->name;
            $data[] = (int) ($counts[$category->id] ?? 0);
        }

        if ($labels === []) {
            $labels = ['—'];
            $data = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => __('brightdor.dashboard.total_bookings'),
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(20, 20, 20, 0.92)',
                        'rgba(196, 165, 116, 0.85)',
                        'rgba(63, 63, 70, 0.8)',
                        'rgba(232, 220, 200, 0.95)',
                        'rgba(20, 20, 20, 0.65)',
                        'rgba(196, 165, 116, 0.55)',
                        'rgba(113, 113, 122, 0.55)',
                        'rgba(247, 243, 235, 1)',
                        'rgba(20, 20, 20, 0.4)',
                        'rgba(168, 132, 74, 0.7)',
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#6b7280',
                        'boxWidth' => 10,
                        'padding' => 14,
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'cutout' => '68%',
            'maintainAspectRatio' => false,
        ];
    }
}
