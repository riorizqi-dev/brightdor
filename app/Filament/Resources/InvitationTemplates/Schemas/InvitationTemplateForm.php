<?php

namespace App\Filament\Resources\InvitationTemplates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvitationTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Template Undangan')
                ->columns(2)
                ->schema([
                    Select::make('invitation_template_category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),
                    TextInput::make('name')->label('Nama Template')->required(),
                    TextInput::make('slug')->unique(ignoreRecord: true),
                    TextInput::make('price')->label('Harga')->numeric()->prefix('Rp')->required(),
                    Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                    TextInput::make('preview_image')->label('Preview Image URL'),
                    TextInput::make('thumbnail')->label('Thumbnail URL'),
                    TextInput::make('demo_url')->label('Demo URL')->url()->columnSpanFull(),
                    TagsInput::make('features')->label('Fitur')->columnSpanFull(),
                    Toggle::make('is_premium')->label('Premium'),
                    Toggle::make('is_featured')->label('Featured'),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                ]),
        ]);
    }
}
