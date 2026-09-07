<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillMediaLibraryFields($data, $this->record);
    }

    protected function afterSave(): void
    {
        $this->saveMediaLibraryFields($this->record, $this->form->getState());
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
