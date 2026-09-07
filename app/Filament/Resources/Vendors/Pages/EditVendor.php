<?php

namespace App\Filament\Resources\Vendors\Pages;

use App\Filament\Concerns\HandlesMediaLibraryUploads;
use App\Filament\Resources\Vendors\VendorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVendor extends EditRecord
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
        ];
    }
}
