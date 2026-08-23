<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VendorPayoutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Payout')
                    ->columns(2)
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('payout_code')->label('Kode Payout'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'info',
                                'completed' => 'success',
                                'failed' => 'danger',
                                'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Menunggu',
                                'processing' => 'Diproses',
                                'completed' => 'Selesai',
                                'failed' => 'Gagal',
                                'cancelled' => 'Dibatalkan',
                                default => $state,
                            }),
                        \Filament\Infolists\Components\TextEntry::make('amount')->label('Jumlah')->money('IDR'),
                        \Filament\Infolists\Components\TextEntry::make('method')->label('Metode'),
                        \Filament\Infolists\Components\TextEntry::make('bank_name')->label('Bank'),
                        \Filament\Infolists\Components\TextEntry::make('bank_account_number')->label('No. Rekening'),
                        \Filament\Infolists\Components\TextEntry::make('bank_account_name')->label('Nama Pemilik'),
                        \Filament\Infolists\Components\TextEntry::make('ewallet_provider')->label('E-Wallet Provider'),
                        \Filament\Infolists\Components\TextEntry::make('ewallet_number')->label('No. E-Wallet'),
                        \Filament\Infolists\Components\TextEntry::make('notes')->label('Catatan')->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('processed_at')->label('Diproses Pada')->dateTime(),
                        \Filament\Infolists\Components\TextEntry::make('completed_at')->label('Selesai Pada')->dateTime(),
                    ]),
            ]);
    }
}