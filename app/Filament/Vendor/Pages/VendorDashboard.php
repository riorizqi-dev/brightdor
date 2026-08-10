<?php

namespace App\Filament\Vendor\Pages;

use App\Models\Vendor;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class VendorDashboard extends Page
{
    protected string $view = 'filament.vendor.pages.vendor-dashboard';

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::Home;

    protected static ?string $slug = 'dashboard';

    protected static ?int $navigationSort = -2;

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

    public function getVendor(): ?Vendor
    {
        return Auth::user()->vendor;
    }

    /**
     * @return array<string, int|string|float|null>
     */
    public function getStats(): array
    {
        $vendor = $this->getVendor();

        if (! $vendor) {
            return [
                'bookings' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'services' => 0,
                'rating_avg' => null,
                'rating_count' => 0,
                'verified' => false,
            ];
        }

        $bookings = $vendor->bookings()->select('status')->get();

        return [
            'bookings' => $bookings->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
            'services' => $vendor->services()->count(),
            'rating_avg' => $vendor->rating_avg,
            'rating_count' => $vendor->rating_count,
            'verified' => (bool) $vendor->is_verified,
        ];
    }

    public function getRecentBookings(): Collection
    {
        $vendor = $this->getVendor();

        if (! $vendor) {
            return collect();
        }

        return $vendor->bookings()
            ->with(['service', 'user'])
            ->latest()
            ->limit(5)
            ->get();
    }
}
