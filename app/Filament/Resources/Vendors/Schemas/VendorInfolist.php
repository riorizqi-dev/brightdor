<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Vendor')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('business_name')->label('Nama Usaha'),
                        TextEntry::make('user.name')->label('Pemilik'),
                        TextEntry::make('category.name')->label('Kategori')->badge(),
                        TextEntry::make('status')->badge(),
                        IconEntry::make('is_verified')->label('Verified')->boolean(),
                        IconEntry::make('is_featured')->label('Featured')->boolean(),
                        TextEntry::make('city')->label('Kota'),
                        TextEntry::make('province')->label('Provinsi'),
                        TextEntry::make('phone')->label('Telepon'),
                        TextEntry::make('whatsapp')->label('WhatsApp'),
                        TextEntry::make('rating_avg')->label('Rating'),
                        TextEntry::make('description')->label('Deskripsi')->columnSpanFull(),
                        TextEntry::make('rejection_reason')->label('Alasan Reject')->columnSpanFull(),
                    ]),
            ]);
    }
}
