<?php

namespace App\Filament\Vendor\Resources\VendorService;

use App\Filament\Vendor\Resources\VendorService\Pages\CreateVendorService;
use App\Filament\Vendor\Resources\VendorService\Pages\EditVendorService;
use App\Filament\Vendor\Resources\VendorService\Pages\ListVendorServices;
use App\Filament\Vendor\Resources\VendorService\Pages\ViewVendorService;
use App\Filament\Vendor\Resources\VendorService\Schemas\VendorServiceForm;
use App\Filament\Vendor\Resources\VendorService\Tables\VendorServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|UnitEnum|null $navigationGroup = 'Jasa Saya';

    public static function getNavigationLabel(): string
    {
        return 'Produk / Jasa Saya';
    }

    protected static ?string $modelLabel = 'Jasa';
    protected static ?string $pluralModelLabel = 'Produk / Jasa';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return VendorServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorServices::route('/'),
            'create' => CreateVendorService::route('/create'),
            'view' => ViewVendorService::route('/{record}'),
            'edit' => EditVendorService::route('/{record}/edit'),
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