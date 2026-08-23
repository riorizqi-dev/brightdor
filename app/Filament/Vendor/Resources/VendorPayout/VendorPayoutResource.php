<?php

namespace App\Filament\Vendor\Resources\VendorPayout;

use App\Filament\Vendor\Resources\VendorPayout\Pages\CreateVendorPayout;
use App\Filament\Vendor\Resources\VendorPayout\Pages\ListVendorPayouts;
use App\Filament\Vendor\Resources\VendorPayout\Pages\ViewVendorPayout;
use App\Filament\Vendor\Resources\VendorPayout\Schemas\VendorPayoutForm;
use App\Filament\Vendor\Resources\VendorPayout\Schemas\VendorPayoutInfolist;
use App\Filament\Vendor\Resources\VendorPayout\Tables\VendorPayoutsTable;
use App\Models\Payout;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorPayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    public static function getNavigationLabel(): string
    {
        return 'Payout Saya';
    }

    protected static ?string $modelLabel = 'Payout';
    protected static ?string $pluralModelLabel = 'Payout';
    protected static ?string $recordTitleAttribute = 'payout_code';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return VendorPayoutForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VendorPayoutInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPayoutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorPayouts::route('/'),
            'create' => CreateVendorPayout::route('/create'),
            'view' => ViewVendorPayout::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('vendor_id', auth()->user()->vendor->id);
    }
}