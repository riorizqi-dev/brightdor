<?php

namespace App\Filament\Vendor\Resources\VendorProfile\Pages;

use App\Filament\Vendor\Resources\VendorProfile\VendorProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorProfile extends CreateRecord
{
    protected static string $resource = VendorProfileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        abort_if(auth()->user()?->vendor, 422, 'Profil vendor sudah ada.');

        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'pending';
        $data['is_verified'] = false;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
