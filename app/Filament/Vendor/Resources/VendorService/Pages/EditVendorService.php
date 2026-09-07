<?php

namespace App\Filament\Vendor\Resources\VendorService\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditVendorService extends EditRecord
{
    use HandlesMediaLibraryUploads;

    protected static string $resource = VendorServiceResource::class;

    protected function getMediaCollectionFields(): array
    {
        return [
            'cover_image' => 'cover',
            'gallery_images' => [
                'collection' => 'gallery',
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