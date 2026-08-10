<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Vendor;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivities extends Widget
{
    protected string $view = 'filament.widgets.recent-activities';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 8,
    ];

    protected static bool $isLazy = true;

    /**
     * @return Collection<int, array{title: string, meta: string, badge: string, tone: string}>
     */
    public function getItems(): Collection
    {
        $vendors = Vendor::query()
            ->with('category:id,name')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Vendor $v) => [
                'title' => $v->business_name,
                'meta' => trim(($v->category?->name ?? 'Vendor') . ' · ' . ($v->city ?: __('brightdor.dashboard.location_unset'))),
                'badge' => __('brightdor.common.pending'),
                'tone' => 'warn',
            ]);

        $bookings = Booking::query()
            ->with(['user:id,name', 'vendor:id,business_name'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Booking $b) => [
                'title' => $b->booking_code . ' · ' . ($b->user?->name ?? 'Customer'),
                'meta' => ($b->vendor?->business_name ?? 'Vendor') . ' · ' . ($b->event_date?->format('d M Y') ?? __('brightdor.dashboard.date_tbd')),
                'badge' => str_replace('_', ' ', $b->status),
                'tone' => match ($b->status) {
                    'completed', 'confirmed' => 'ok',
                    'pending', 'on_progress' => 'warn',
                    default => 'default',
                },
            ]);

        return $vendors->concat($bookings)->take(8)->values();
    }
}
