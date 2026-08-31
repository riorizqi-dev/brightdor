<?php

namespace App\Filament\Vendor\Resources\VendorBooking;

use App\Filament\Vendor\Resources\VendorBooking\Pages\ListVendorBookings;
use App\Filament\Vendor\Resources\VendorBooking\Pages\ViewVendorBooking;
use App\Filament\Vendor\Resources\VendorBooking\Schemas\VendorBookingInfolist;
use App\Filament\Vendor\Resources\VendorBooking\Tables\VendorBookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorBookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Booking Saya';

    public static function getNavigationLabel(): string
    {
        return 'Booking Masuk';
    }

    protected static ?string $modelLabel = 'Booking';
    protected static ?string $pluralModelLabel = 'Booking';
    protected static ?string $recordTitleAttribute = 'booking_code';
    protected static ?int $navigationSort = 1;

    public static function infolist(Schema $schema): Schema
    {
        return VendorBookingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorBookingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorBookings::route('/'),
            'view' => ViewVendorBooking::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $vendorId = auth()->user()?->vendor?->id;

        if (! $vendorId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('vendor_id', $vendorId);
    }
}