<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking')
                    ->columns(2)
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto-generated'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'on_progress' => 'On Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'refund' => 'Refund',
                            ])
                            ->required()
                            ->default('pending'),
                        Select::make('user_id')
                            ->label('Customer (Couple)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->relationship('vendor', 'business_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('service_id')
                            ->label('Jasa / Paket')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload(),
                        DatePicker::make('event_date')->label('Tanggal Acara'),
                        TimePicker::make('event_time')->label('Waktu'),
                        TextInput::make('event_location')->label('Lokasi Acara')->columnSpanFull(),
                        TextInput::make('guest_count')->label('Jumlah Tamu')->numeric(),
                    ]),
                Section::make('Keuangan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('subtotal')->numeric()->prefix('Rp')->default(0),
                        TextInput::make('discount')->label('Diskon')->numeric()->prefix('Rp')->default(0),
                        TextInput::make('admin_fee')->label('Admin Fee')->numeric()->prefix('Rp')->default(0),
                        TextInput::make('commission_amount')->label('Komisi')->numeric()->prefix('Rp')->default(0),
                        TextInput::make('total_amount')->label('Total')->numeric()->prefix('Rp')->default(0),
                    ]),
                Section::make('Catatan')
                    ->schema([
                        Textarea::make('customer_notes')->label('Catatan Customer')->rows(2),
                        Textarea::make('admin_notes')->label('Catatan Internal Admin')->rows(3),
                        Textarea::make('cancellation_reason')->label('Alasan Batal')->rows(2),
                    ]),
            ]);
    }
}
