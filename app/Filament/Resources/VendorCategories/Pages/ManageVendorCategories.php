<?php

namespace App\Filament\Resources\VendorCategories\Pages;

use App\Filament\Resources\VendorCategories\VendorCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVendorCategories extends ManageRecords
{
    protected static string $resource = VendorCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
