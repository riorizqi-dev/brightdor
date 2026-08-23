<?php

namespace App\Filament\Vendor\Resources\VendorProfile\Pages;

use App\Filament\Vendor\Resources\VendorProfile\VendorProfileResource;
use Filament\Resources\Pages\EditRecord;

class EditVendorProfile extends EditRecord
{
    protected static string $resource = VendorProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}