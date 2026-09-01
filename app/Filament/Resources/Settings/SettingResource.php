<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\ManageSettings;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('brightdor.nav.dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('brightdor.nav.general_settings');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('group')
                ->options([
                    'general' => 'General',
                    'commission' => 'Commission',
                    'payment' => 'Payment Gateway',
                    'email' => 'Email & Notification',
                    'social' => 'Social Media',
                ])
                ->required()
                ->default('general'),
            TextInput::make('key')->required()->unique(ignoreRecord: true),
            Select::make('type')->options([
                'string' => 'String',
                'number' => 'Number',
                'boolean' => 'Boolean',
                'json' => 'JSON',
                'file' => 'File',
            ])->default('string')->required(),
            Textarea::make('value')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->badge()->sortable(),
                TextColumn::make('key')->searchable()->weight('bold'),
                TextColumn::make('type')->badge()->color('gray'),
                TextColumn::make('value')->limit(40),
            ])
            ->filters([
                SelectFilter::make('group')->options([
                    'general' => 'General',
                    'commission' => 'Commission',
                    'payment' => 'Payment',
                    'email' => 'Email',
                    'social' => 'Social',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSettings::route('/')];
    }
}
