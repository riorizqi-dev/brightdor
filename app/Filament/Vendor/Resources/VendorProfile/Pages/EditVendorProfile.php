<?php

namespace App\Filament\Vendor\Resources\VendorProfile\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Vendor\Resources\VendorProfile\VendorProfileResource;
use Filament\Resources\Pages\EditRecord;

class EditVendorProfile extends EditRecord
{
    use HandlesMediaLibraryUploads;

    protected static string $resource = VendorProfileResource::class;

    protected function getMediaCollectionFields(): array
    {
        return [
            'logo' => 'logo',
            'portfolio_images' => [
                'collection' => 'portfolio',
                'multiple' => true,
            ],
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillMediaLibraryFields($data, $this->record);
    }

    protected function afterSave(): void
    {
        $this->saveMediaLibraryFields($this->record, $this->form->getState());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}