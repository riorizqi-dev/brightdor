<?php

namespace App\Providers\Filament;

use App\Filament\Vendor\Pages\VendorDashboard;
use App\Filament\Vendor\Resources\VendorBooking\VendorBookingResource;
use App\Filament\Vendor\Resources\VendorPayout\VendorPayoutResource;
use App\Filament\Vendor\Resources\VendorProfile\VendorProfileResource;
use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use App\Http\Middleware\SetLocale;
use App\Support\BrandPalette;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class VendorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('vendor')
            ->path('vendor')
            ->login(\App\Filament\Auth\Login::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('BrightDor')
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon.ico'))
            // BrightDor brand: pink / maroon / gold (mirror admin)
            ->colors([
                'primary' => Color::hex(BrandPalette::ROSE_600),
                'secondary' => Color::hex(BrandPalette::GOLD_500),
                'gray' => Color::Zinc,
                'success' => Color::hex('#3f7d5c'),
                'warning' => Color::hex(BrandPalette::GOLD_600),
                'danger' => Color::hex(BrandPalette::ROSE_800),
                'info' => Color::hex(BrandPalette::ROSE_500),
            ])
            ->font('Inter', provider: GoogleFontProvider::class)
            // Light elegant default
            ->darkMode(false)
            ->defaultThemeMode(ThemeMode::Light)
            // TOP NAV ONLY
            ->topNavigation()
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Vendor/Resources'), for: 'App\\Filament\\Vendor\\Resources')
            ->discoverPages(in: app_path('Filament/Vendor/Pages'), for: 'App\\Filament\\Vendor\\Pages')
            ->pages([
                VendorDashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Fraunces is the display face used across the customer site; load
            // it here rather than via a CSS @import (which the bundler drops).
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => <<<'HTML'
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&display=swap" rel="stylesheet">
                    HTML,
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                function (): string {
                    $locale = app()->getLocale();
                    $dir = SetLocale::SUPPORTED[$locale]['dir'] ?? 'ltr';

                    return Blade::render(
                        '<script>document.documentElement.setAttribute("dir", @js($dir)); document.documentElement.setAttribute("lang", @js($locale));</script>',
                        ['dir' => $dir, 'locale' => $locale],
                    );
                },
            );
    }
}
