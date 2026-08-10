<?php

namespace App\Filament\Resources\CommissionSettings\Pages;

use App\Filament\Resources\CommissionSettings\CommissionSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCommissionSettings extends ManageRecords
{
    protected static string $resource = CommissionSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
