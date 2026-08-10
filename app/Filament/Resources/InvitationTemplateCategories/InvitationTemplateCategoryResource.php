<?php

namespace App\Filament\Resources\InvitationTemplateCategories;

use App\Filament\Resources\InvitationTemplateCategories\Pages\ManageInvitationTemplateCategories;
use App\Models\InvitationTemplateCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class InvitationTemplateCategoryResource extends Resource
{
    protected static ?string $model = InvitationTemplateCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.invitations');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.invitation_template_categories');
    }

    protected static ?string $modelLabel = 'Kategori Template';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama')->required()->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str($state)->slug())),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug'),
                TextColumn::make('templates_count')->counts('templates')->label('Template'),
                IconColumn::make('is_active')->boolean()->label('Aktif'),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInvitationTemplateCategories::route('/'),
        ];
    }
}
