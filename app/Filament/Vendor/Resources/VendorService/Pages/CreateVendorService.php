<?php

namespace App\Filament\Vendor\Resources\VendorService\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Vendor\Resources\VendorService\VendorServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorService extends CreateRecord
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $vendor = auth()->user()?->vendor;
        abort_unless($vendor, 403, 'Profil vendor belum tersedia. Lengkapi profil vendor terlebih dahulu.');

        $data['vendor_id'] = $vendor->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->saveMediaLibraryFields($this->record, $this->form->getState());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}