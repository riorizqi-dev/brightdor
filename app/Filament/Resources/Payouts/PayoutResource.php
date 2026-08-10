<?php

namespace App\Filament\Resources\Payouts;

use App\Filament\Resources\Payouts\Pages\CreatePayout;
use App\Filament\Resources\Payouts\Pages\EditPayout;
use App\Filament\Resources\Payouts\Pages\ListPayouts;
use App\Filament\Resources\Payouts\Pages\ViewPayout;
use App\Filament\Resources\Payouts\Schemas\PayoutForm;
use App\Filament\Resources\Payouts\Schemas\PayoutInfolist;
use App\Filament\Resources\Payouts\Tables\PayoutsTable;
use App\Models\Payout;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.finance');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.payouts');
    }

    protected static ?string $recordTitleAttribute = 'payout_code';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = cache()->remember('nav.badge.payouts.pending', 60, fn () => static::getModel()::query()->where('status', 'pending')->count());

        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return PayoutForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayoutInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayoutsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayouts::route('/'),
            'create' => CreatePayout::route('/create'),
            'view' => ViewPayout::route('/{record}'),
            'edit' => EditPayout::route('/{record}/edit'),
        ];
    }
}
