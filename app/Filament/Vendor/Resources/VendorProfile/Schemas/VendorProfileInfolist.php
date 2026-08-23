<?php

namespace App\Filament\Vendor\Resources\VendorProfile\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VendorProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('business_name')->label('Nama Usaha'),
                        TextEntry::make('category.name')->label('Kategori'),
                        TextEntry::make('slug')->label('Slug'),
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('description')->label('Deskripsi')->columnSpanFull(),
                        TextEntry::make('address')->label('Alamat')->columnSpanFull(),
                        TextEntry::make('city')->label('Kota'),
                        TextEntry::make('province')->label('Provinsi'),
                        TextEntry::make('postal_code')->label('Kode Pos'),
                        TextEntry::make('phone')->label('Telepon'),
                        TextEntry::make('whatsapp')->label('WhatsApp'),
                        TextEntry::make('website')->label('Website')->url(),
                        TextEntry::make('instagram')->label('Instagram'),
                        TextEntry::make('is_verified')->label('Terverifikasi')->boolean(),
                        TextEntry::make('is_featured')->label('Featured')->boolean(),
                        TextEntry::make('verified_at')->label('Diverifikasi Pada')->dateTime(),
                    ]),
                Section::make('Akun Bank')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('bank_name')->label('Nama Bank'),
                        TextEntry::make('bank_account_number')->label('Nomor Rekening'),
                        TextEntry::make('bank_account_name')->label('Nama Pemilik')->columnSpanFull(),
                    ]),
                Section::make('Lokasi GPS')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('latitude')->label('Latitude'),
                        TextEntry::make('longitude')->label('Longitude'),
                    ]),
            ]);
    }
}