<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorPayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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