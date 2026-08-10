<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->autocomplete(false);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->autocomplete(false);
    }

    public function getSubheading(): string | Htmlable | null
    {
        return new HtmlString('Belum punya akun? ' . Blade::render(
            '<x-filament::link :href="route(\'vendors.register.create\')" tabindex="-1">Daftar di sini</x-filament::link>'
        ));
    }
}
