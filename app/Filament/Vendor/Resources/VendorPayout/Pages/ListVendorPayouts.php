<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Pages;

use App\Filament\Vendor\Resources\VendorPayout\VendorPayoutResource;
use App\Filament\Vendor\Widgets\PayoutBalanceWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPayouts extends ListRecords
{
    protected static string $resource = VendorPayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PayoutBalanceWidget::make(),
        ];
    }
}