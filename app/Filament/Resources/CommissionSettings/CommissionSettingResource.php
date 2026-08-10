<?php

namespace App\Filament\Resources\CommissionSettings;

use App\Filament\Resources\CommissionSettings\Pages\ManageCommissionSettings;
use App\Models\CommissionSetting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CommissionSettingResource extends Resource
{
    protected static ?string $model = CommissionSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPercentBadge;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.commissions');
    }

    protected static ?string $modelLabel = 'Commission';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->label('Label')->placeholder('Global default / per kategori'),
            Select::make('vendor_category_id')
                ->label('Kategori (kosong = global)')
                ->relationship('category', 'name')
                ->searchable()
                ->preload(),
            TextInput::make('rate_percent')->label('Rate (%)')->numeric()->suffix('%')->required()->default(10),
            TextInput::make('rate_fixed')->label('Rate Fixed (Rp)')->numeric()->prefix('Rp')->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('Label')->searchable(),
                TextColumn::make('category.name')->label('Kategori')->placeholder('Global')->badge(),
                TextColumn::make('rate_percent')->label('%')->suffix('%'),
                TextColumn::make('rate_fixed')->label('Fixed')->money('IDR'),
                IconColumn::make('is_active')->boolean()->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCommissionSettings::route('/'),
        ];
    }
}
