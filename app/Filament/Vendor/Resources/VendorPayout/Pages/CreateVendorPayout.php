<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Pages;

use App\Filament\Vendor\Resources\VendorPayout\VendorPayoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorPayout extends CreateRecord
{
    protected static string $resource = VendorPayoutResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Profil vendor belum tersedia.');

        $data['vendor_id'] = $vendor->id;
        $data['status'] = 'pending';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}