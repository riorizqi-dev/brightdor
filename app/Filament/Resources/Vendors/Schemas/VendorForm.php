<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\User;
use App\Models\VendorCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Usaha')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->email()->required(),
                                TextInput::make('password')->password()->revealable()->required(),
                                Select::make('user_type')->options([
                                    'vendor' => 'Vendor',
                                ])->default('vendor')->required(),
                            ]),
                        Select::make('vendor_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('business_name')
                            ->label('Nama Usaha')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Lokasi & Kontak')
                    ->columns(2)
                    ->schema([
                        TextInput::make('address')->label('Alamat')->columnSpanFull(),
                        TextInput::make('city')->label('Kota'),
                        TextInput::make('province')->label('Provinsi'),
                        TextInput::make('postal_code')->label('Kode Pos'),
                        TextInput::make('phone')->label('Telepon')->tel(),
                        TextInput::make('whatsapp')->label('WhatsApp')->tel(),
                        TextInput::make('website')->url()->label('Website'),
                        TextInput::make('instagram')->label('Instagram'),
                        TextInput::make('portfolio_url')->url()->label('Portfolio URL')->columnSpanFull(),
                    ]),
                Section::make('Bank & Status')
                    ->columns(2)
                    ->schema([
                        TextInput::make('bank_name')->label('Bank'),
                        TextInput::make('bank_account_number')->label('No. Rekening'),
                        TextInput::make('bank_account_name')->label('Atas Nama')->columnSpanFull(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'suspended' => 'Suspended',
                            ])
                            ->required()
                            ->default('pending'),
                        Toggle::make('is_verified')->label('Terverifikasi'),
                        Toggle::make('is_featured')->label('Featured'),
                        Textarea::make('rejection_reason')
                            ->label('Alasan Reject')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
