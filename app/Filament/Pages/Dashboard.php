<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingsByCategoryChart;
use App\Filament\Widgets\PremiumStatsOverview;
use App\Filament\Widgets\QuickActions;
use App\Filament\Widgets\RecentActivities;
use App\Filament\Widgets\RevenueChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::Home;

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?int $navigationSort = -2;

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return __('brightdor.nav.dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.dashboard');
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return null;
    }

    /**
     * @return array<class-string | \Filament\Widgets\WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            PremiumStatsOverview::class,
            RevenueChart::class,
            BookingsByCategoryChart::class,
            QuickActions::class,
            RecentActivities::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'lg' => 12,
        ];
    }
}
