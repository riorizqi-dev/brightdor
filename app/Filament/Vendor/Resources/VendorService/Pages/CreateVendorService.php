<?php

namespace App\Filament\Vendor\Resources\VendorService\Pages;

use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorService extends CreateRecord
{
    protected static string $resource = VendorServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = auth()->user()->vendor->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}