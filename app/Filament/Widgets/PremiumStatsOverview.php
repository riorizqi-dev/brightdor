<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\InvitationOrder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use Filament\Widgets\Widget;

class PremiumStatsOverview extends Widget
{
    protected string $view = 'filament.widgets.premium-stats-overview';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getStats(): array
    {
        $pendingVendors = Vendor::query()->where('status', 'pending')->count();
        $pendingBookings = Booking::query()->where('status', 'pending')->count();
        $revenue = (float) Transaction::query()
            ->where('status', 'success')
            ->where('type', 'payment')
            ->sum('amount');
        $invitations = InvitationOrder::query()
            ->whereIn('status', ['paid', 'active'])
            ->count();

        return [
            [
                'label' => __('brightdor.dashboard.total_vendors'),
                'value' => number_format(Vendor::query()->count()),
                'hint' => $pendingVendors > 0
                    ? __('brightdor.dashboard.pending_approval', ['count' => $pendingVendors])
                    : __('brightdor.dashboard.all_verified'),
                'icon' => 'building',
            ],
            [
                'label' => __('brightdor.dashboard.total_bookings'),
                'value' => number_format(Booking::query()->count()),
                'hint' => $pendingBookings > 0
                    ? __('brightdor.dashboard.awaiting_confirm', ['count' => $pendingBookings])
                    : __('brightdor.dashboard.no_queue'),
                'icon' => 'calendar',
            ],
            [
                'label' => __('brightdor.dashboard.revenue'),
                'value' => 'Rp ' . number_format($revenue, 0, ',', '.'),
                'hint' => __('brightdor.dashboard.successful_payments'),
                'icon' => 'banknotes',
            ],
            [
                'label' => __('brightdor.dashboard.couples'),
                'value' => number_format(User::query()->where('user_type', 'couple')->count()),
                'hint' => __('brightdor.dashboard.registered_customers'),
                'icon' => 'users',
            ],
            [
                'label' => __('brightdor.dashboard.digital_invites'),
                'value' => number_format($invitations),
                'hint' => __('brightdor.dashboard.paid_active_orders'),
                'icon' => 'envelope',
            ],
        ];
    }

    public function getGreeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 11 => __('brightdor.dashboard.greeting_morning'),
            $hour < 15 => __('brightdor.dashboard.greeting_afternoon'),
            $hour < 18 => __('brightdor.dashboard.greeting_evening'),
            default => __('brightdor.dashboard.greeting_night'),
        };
    }

    public function getUserName(): string
    {
        return auth()->user()?->name ?? 'Admin';
    }
}
