<?php

namespace App\Filament\Resources\InvitationOrders\Pages;

use App\Filament\Resources\InvitationOrders\InvitationOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInvitationOrder extends ViewRecord
{
    protected static string $resource = InvitationOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
