<?php

namespace App\Filament\Vendor\Resources\VendorService\Pages;

use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditVendorService extends EditRecord
{
    protected static string $resource = VendorServiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}