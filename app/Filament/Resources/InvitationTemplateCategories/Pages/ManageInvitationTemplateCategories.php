<?php

namespace App\Filament\Resources\InvitationTemplateCategories\Pages;

use App\Filament\Resources\InvitationTemplateCategories\InvitationTemplateCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInvitationTemplateCategories extends ManageRecords
{
    protected static string $resource = InvitationTemplateCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
