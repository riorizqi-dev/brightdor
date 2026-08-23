<?php

namespace App\Filament\Vendor\Resources\VendorBooking\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VendorBookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Booking')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('booking_code')->label('Kode Booking'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'on_progress' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'refund' => 'secondary',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Menunggu',
                                'confirmed' => 'Terkonfirmasi',
                                'on_progress' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'refund' => 'Refund',
                                default => $state,
                            }),
                        TextEntry::make('user.name')->label('Customer'),
                        TextEntry::make('user.email')->label('Email Customer'),
                        TextEntry::make('user.phone')->label('Telepon Customer'),
                        TextEntry::make('service.name')->label('Paket / Jasa'),
                        TextEntry::make('event_date')->label('Tanggal Acara')->date('d M Y'),
                        TextEntry::make('event_time')->label('Waktu')->time('H:i'),
                        TextEntry::make('event_location')->label('Lokasi'),
                        TextEntry::make('guest_count')->label('Jumlah Tamu'),
                        TextEntry::make('total_amount')->label('Total')->money('IDR'),
                        TextEntry::make('customer_notes')->label('Catatan Customer')->columnSpanFull(),
                        TextEntry::make('cancellation_reason')->label('Alasan Pembatalan')->columnSpanFull(),
                    ]),
            ]);
    }
}