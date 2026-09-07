<?php

namespace App\Filament\Resources\Vendors\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Resources\Vendors\VendorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendor extends CreateRecord
{
    use HandlesMediaLibraryUploads;

    protected static string $resource = VendorResource::class;

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

    protected function afterCreate(): void
    {
        $this->saveMediaLibraryFields($this->record, $this->form->getState());
    }
}
