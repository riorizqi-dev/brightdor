<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Artikel')
                ->columns(2)
                ->schema([
                    TextInput::make('title')->label('Judul')->required()->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str($state)->slug())),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Select::make('user_id')->label('Author')->relationship('author', 'name')->searchable()->preload(),
                    Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft'),
                    Textarea::make('excerpt')->label('Ringkasan')->rows(2)->columnSpanFull(),
                    RichEditor::make('content')->label('Konten')->columnSpanFull(),
                    TextInput::make('cover_image')->label('Cover Image URL')->columnSpanFull(),
                    Toggle::make('is_featured')->label('Featured'),
                    DateTimePicker::make('published_at')->label('Publish At'),
                ]),
        ]);
    }
}
