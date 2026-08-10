<?php

namespace App\Filament\Resources\InvitationOrders;

use App\Filament\Resources\InvitationOrders\Pages\CreateInvitationOrder;
use App\Filament\Resources\InvitationOrders\Pages\EditInvitationOrder;
use App\Filament\Resources\InvitationOrders\Pages\ListInvitationOrders;
use App\Filament\Resources\InvitationOrders\Pages\ViewInvitationOrder;
use App\Filament\Resources\InvitationOrders\Schemas\InvitationOrderForm;
use App\Filament\Resources\InvitationOrders\Schemas\InvitationOrderInfolist;
use App\Filament\Resources\InvitationOrders\Tables\InvitationOrdersTable;
use App\Models\InvitationOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InvitationOrderResource extends Resource
{
    protected static ?string $model = InvitationOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.invitations');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.invitation_orders');
    }

    protected static ?string $recordTitleAttribute = 'order_code';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return InvitationOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvitationOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvitationOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvitationOrders::route('/'),
            'create' => CreateInvitationOrder::route('/create'),
            'view' => ViewInvitationOrder::route('/{record}'),
            'edit' => EditInvitationOrder::route('/{record}/edit'),
        ];
    }
}
