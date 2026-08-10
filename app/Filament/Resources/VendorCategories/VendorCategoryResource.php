<?php

namespace App\Filament\Resources\VendorCategories;

use App\Filament\Resources\VendorCategories\Pages\ManageVendorCategories;
use App\Models\VendorCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class VendorCategoryResource extends Resource
{
    protected static ?string $model = VendorCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.vendors');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.vendor_categories');
    }

    protected static ?string $modelLabel = 'Kategori Vendor';

    protected static ?string $pluralModelLabel = 'Kategori Vendor';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str($state)->slug())),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('icon')
                    ->label('Icon (Heroicon name)')
                    ->placeholder('building-storefront'),
                TextInput::make('commission_rate')
                    ->label('Komisi Default (%)')
                    ->numeric()
                    ->default(10)
                    ->suffix('%')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->toggleable(),
                TextColumn::make('commission_rate')
                    ->label('Komisi')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('vendors_count')
                    ->counts('vendors')
                    ->label('Vendor'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageVendorCategories::route('/'),
        ];
    }
}
