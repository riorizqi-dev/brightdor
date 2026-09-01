<?php

namespace App\Filament\Resources\InvitationTemplates;

use App\Filament\Resources\InvitationTemplates\Pages\CreateInvitationTemplate;
use App\Filament\Resources\InvitationTemplates\Pages\EditInvitationTemplate;
use App\Filament\Resources\InvitationTemplates\Pages\ListInvitationTemplates;
use App\Filament\Resources\InvitationTemplates\Pages\ViewInvitationTemplate;
use App\Filament\Resources\InvitationTemplates\Schemas\InvitationTemplateForm;
use App\Filament\Resources\InvitationTemplates\Schemas\InvitationTemplateInfolist;
use App\Filament\Resources\InvitationTemplates\Tables\InvitationTemplatesTable;
use App\Models\InvitationTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InvitationTemplateResource extends Resource
{
    protected static ?string $model = InvitationTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.marketplace');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.invitation_templates');
    }

    protected static ?string $modelLabel = 'Template';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return InvitationTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InvitationTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvitationTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvitationTemplates::route('/'),
            'create' => CreateInvitationTemplate::route('/create'),
            'view' => ViewInvitationTemplate::route('/{record}'),
            'edit' => EditInvitationTemplate::route('/{record}/edit'),
        ];
    }
}
