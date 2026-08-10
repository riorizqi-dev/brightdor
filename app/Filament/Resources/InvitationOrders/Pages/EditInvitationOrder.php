<?php

namespace App\Filament\Resources\InvitationOrders\Pages;

use App\Filament\Resources\InvitationOrders\InvitationOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInvitationOrder extends EditRecord
{
    protected static string $resource = InvitationOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
