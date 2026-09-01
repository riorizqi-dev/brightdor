<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    /**
     * Start every visit with an empty form.
     *
     * Filament itself never seeds credentials, but Livewire can restore a
     * previous component state on back-navigation. Blanking here guarantees the
     * fields render empty.
     */
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => null,
            'password' => null,
            'remember' => false,
        ]);
    }

    protected function getEmailFormComponent(): Component
    {
        // Chrome ignores autocomplete="off" on login forms and fills the saved
        // credential anyway. "new-password" is the value it actually honours.
        return parent::getEmailFormComponent()
            ->autocomplete('off')
            ->extraInputAttributes([
                'autocomplete' => 'new-password',
                'data-form-type' => 'other',
            ], merge: true);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->autocomplete('new-password')
            ->revealable()
            ->extraInputAttributes([
                'autocomplete' => 'new-password',
                'data-form-type' => 'other',
            ], merge: true);
    }

    public function getSubheading(): string | Htmlable | null
    {
        return new HtmlString('Belum punya akun? ' . Blade::render(
            '<x-filament::link :href="route(\'frontend.register.create\')" tabindex="-1">Daftar sebagai user di sini</x-filament::link>'
        ));
    }
}
