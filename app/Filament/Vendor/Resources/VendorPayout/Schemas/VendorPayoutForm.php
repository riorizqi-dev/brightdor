<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class VendorPayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        $available = auth()->user()->vendor?->payoutsAvailable() ?? 0;

        return $schema
            ->components([
                Section::make('Saldo Tersedia')
                    ->schema([
                        Placeholder::make('balance_info')
                            ->label('')
                            ->content(new HtmlString(
                                '<div style="display:flex;align-items:baseline;gap:0.5rem;">'
                                . '<span style="font-family:Fraunces,serif;font-size:1.75rem;font-weight:600;color:#2a2520;letter-spacing:-0.02em;">Rp '
                                . number_format($available, 0, ',', '.')
                                . '</span>'
                                . '<span style="font-size:0.8rem;color:#8f837a;">siap ditarik</span>'
                                . '</div>'
                                . '<p style="margin-top:0.35rem;font-size:0.8rem;color:#8f837a;">Saldo dari acara yang sudah selesai, setelah potongan komisi BrightDor.</p>'
                            )),
                    ]),
                Section::make('Ajukan Payout')
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Jumlah (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(10000)
                            ->maxValue(fn ($get, $record) => auth()->user()->vendor?->payoutsAvailable() ?? 0)
                            ->helperText('Maksimal: Rp' . number_format(auth()->user()->vendor?->payoutsAvailable() ?? 0, 0, ',', '.')),
                        Select::make('method')
                            ->label('Metode')
                            ->options([
                                'bank_transfer' => 'Transfer Bank',
                                'ewallet' => 'E-Wallet',
                            ])
                            ->default('bank_transfer')
                            ->required(),
                        TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->visible(fn ($get) => $get('method') === 'bank_transfer')
                            ->required(),
                        TextInput::make('bank_account_number')
                            ->label('Nomor Rekening')
                            ->visible(fn ($get) => $get('method') === 'bank_transfer')
                            ->required(),
                        TextInput::make('bank_account_name')
                            ->label('Nama Pemilik Rekening')
                            ->visible(fn ($get) => $get('method') === 'bank_transfer')
                            ->required(),
                        TextInput::make('ewallet_provider')
                            ->label('Provider E-Wallet')
                            ->visible(fn ($get) => $get('method') === 'ewallet')
                            ->placeholder('Dana, OVO, GoPay, ShopeePay'),
                        TextInput::make('ewallet_number')
                            ->label('Nomor E-Wallet')
                            ->visible(fn ($get) => $get('method') === 'ewallet')
                            ->required(),
                    ]),
            ]);
    }
}