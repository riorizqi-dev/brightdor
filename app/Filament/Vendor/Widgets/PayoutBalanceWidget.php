<?php

namespace App\Filament\Vendor\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayoutBalanceWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $vendor = auth()->user()?->vendor;

        if (! $vendor) {
            return [];
        }

        $available = $vendor->payoutsAvailable();

        return [
            Stat::make(
                __('brightdor.vendor_dashboard.available_balance'),
                'Rp ' . number_format($available, 0, ',', '.'),
            )
                ->description($available > 0
                    ? __('brightdor.vendor_dashboard.available_balance_hint')
                    : __('brightdor.vendor_dashboard.no_balance'))
                ->icon('heroicon-o-banknotes')
                ->color($available > 0 ? 'success' : 'gray'),
        ];
    }
}