<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Http\Middleware\SetLocale;
use App\Support\BrandPalette;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\Login::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('BrightDor')
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon.ico'))
            // BrightDor brand: pink / maroon / gold (mirrors the customer site)
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
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn (): ?string => '⌘K')
            ->sidebarCollapsibleOnDesktop(false)
            ->navigationGroups([
                NavigationGroup::make(fn () => __('brightdor.nav.dashboard')),
                NavigationGroup::make(fn () => __('brightdor.nav.vendors')),
                NavigationGroup::make(fn () => __('brightdor.nav.marketplace')),
                NavigationGroup::make(fn () => __('brightdor.nav.finance')),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
