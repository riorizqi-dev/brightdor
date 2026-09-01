<?php

namespace App\Filament\Pages;

use App\Http\Middleware\SetLocale;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class LanguageSettings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string | UnitEnum | null $navigationGroup = null;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.language-settings';

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return __('brightdor.nav.dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.language_settings');
    }

    public function getTitle(): string
    {
        return __('brightdor.language.title');
    }

    public function getHeading(): string
    {
        return __('brightdor.language.heading');
    }

    public function getSubheading(): ?string
    {
        return __('brightdor.language.description');
    }

    /**
     * @return array<string, array{name: string, native: string, dir: string}>
     */
    public function getLocales(): array
    {
        return SetLocale::SUPPORTED;
    }

    public function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    public function switchLocale(string $locale): void
    {
        if (! array_key_exists($locale, SetLocale::SUPPORTED)) {
            return;
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        Notification::make()
            ->title(__('brightdor.language.updated'))
            ->success()
            ->send();

        $this->redirect(static::getUrl(), navigate: false);
    }
}
