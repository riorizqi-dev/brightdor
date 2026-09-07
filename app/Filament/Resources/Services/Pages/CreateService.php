<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use HandlesMediaLibraryUploads;

    protected static string $resource = ServiceResource::class;

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

    protected function afterCreate(): void
    {
        $this->saveMediaLibraryFields($this->record, $this->form->getState());
    }
}
