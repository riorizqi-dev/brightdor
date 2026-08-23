<?php

namespace App\Filament\Vendor\Resources\VendorService\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jasa')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Paket / Jasa')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('discount_price')
                            ->label('Harga Diskon')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('price_unit')
                            ->label('Satuan Harga')
                            ->placeholder('per pax / per event'),
                        TextInput::make('capacity')->label('Kapasitas')->numeric(),
                        TextInput::make('duration')->label('Durasi'),
                        TextInput::make('location')->label('Lokasi'),
                        TagsInput::make('features')->label('Fitur')->columnSpanFull(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'moderated' => 'Moderated',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),
                        Toggle::make('is_featured')->label('Featured / Highlight'),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                    ]),
            ]);
    }
}