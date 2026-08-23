<?php

namespace App\Filament\Vendor\Resources\VendorProfile;

use App\Filament\Vendor\Resources\VendorProfile\Pages\EditVendorProfile;
use App\Filament\Vendor\Resources\VendorProfile\Pages\ListVendorProfiles;
use App\Filament\Vendor\Resources\VendorProfile\Schemas\VendorProfileForm;
use App\Filament\Vendor\Resources\VendorProfile\Schemas\VendorProfileInfolist;
use App\Models\Vendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorProfileResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Profil';

    public static function getNavigationLabel(): string
    {
        return 'Profil Saya';
    }

    protected static ?string $modelLabel = 'Profil';
    protected static ?string $pluralModelLabel = 'Profil';
    protected static ?string $recordTitleAttribute = 'business_name';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return VendorProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VendorProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('business_name')->label('Nama Usaha'),
                \Filament\Tables\Columns\TextColumn::make('city')->label('Kota'),
                \Filament\Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorProfiles::route('/'),
            'edit' => EditVendorProfile::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}