<?php

namespace App\Filament\Vendor\Resources\VendorProfile\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_category_id')
                            ->label('Kategori Vendor')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('business_name')
                            ->label('Nama Usaha')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Digunakan untuk URL publik vendor'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('address')->label('Alamat Lengkap')->columnSpanFull(),
                        TextInput::make('city')->label('Kota')->required(),
                        TextInput::make('province')->label('Provinsi'),
                        TextInput::make('postal_code')->label('Kode Pos'),
                        TextInput::make('phone')->label('Telepon'),
                        TextInput::make('whatsapp')->label('WhatsApp'),
                        TextInput::make('website')->label('Website')->url(),
                        TextInput::make('instagram')->label('Instagram'),
                        Toggle::make('is_featured')->label('Featured / Highlight')->disabled()->helperText('Hanya admin yang dapat mengatur.'),
                        Toggle::make('is_verified')->label('Terverifikasi')->disabled()->helperText('Status verifikasi oleh admin.'),
                    ]),
                Section::make('Akun Bank (untuk Payout)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('bank_name')->label('Nama Bank'),
                        TextInput::make('bank_account_number')->label('Nomor Rekening'),
                        TextInput::make('bank_account_name')->label('Nama Pemilik Rekening')->columnSpanFull(),
                    ]),
                Section::make('Lokasi GPS (Opsional)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('latitude')->label('Latitude')->numeric()->step('any'),
                        TextInput::make('longitude')->label('Longitude')->numeric()->step('any'),
                    ]),
                Section::make('Logo & Foto Portofolio Bisnis')
                    ->description('Unggah logo bisnis dan foto portofolio terbaik Anda untuk ditampilkan di halaman profil vendor dan kartu pencarian.')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Usaha / Brand')
                            ->image()
                            ->disk('public')
                            ->directory('vendors/logos')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Logo atau avatar bisnis resmi (JPG, PNG, atau WEBP, maks. 2MB).'),
                        FileUpload::make('portfolio_images')
                            ->label('Foto Portofolio & Galeri Karya')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('vendors/portfolio')
                            ->visibility('public')
                            ->maxFiles(10)
                            ->maxSize(5120)
                            ->helperText('Foto portofolio terbaik yang akan menjadi cover utama dan galeri profil vendor Anda (maks. 10 foto).'),
                    ]),
            ]);
    }
}