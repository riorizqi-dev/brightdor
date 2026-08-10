<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Http\Middleware\SetLocale;
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
            // Cream / black / soft gold accent
            ->colors([
                'primary' => Color::hex('#141414'),
                'secondary' => Color::hex('#c4a574'),
                'gray' => Color::Zinc,
                'success' => Color::hex('#3f3f46'),
                'warning' => Color::hex('#a8844a'),
                'danger' => Color::Rose,
                'info' => Color::hex('#52525b'),
            ])
            ->font('Plus Jakarta Sans', provider: GoogleFontProvider::class)
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
                NavigationGroup::make(fn () => __('brightdor.nav.invitations')),
                NavigationGroup::make(fn () => __('brightdor.nav.finance')),
                NavigationGroup::make(fn () => __('brightdor.nav.content')),
                NavigationGroup::make(fn () => __('brightdor.nav.settings')),
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
