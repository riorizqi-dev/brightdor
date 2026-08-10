<?php

namespace App\Filament\Resources\InvitationOrders\Pages;

use App\Filament\Resources\InvitationOrders\InvitationOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvitationOrders extends ListRecords
{
    protected static string $resource = InvitationOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
