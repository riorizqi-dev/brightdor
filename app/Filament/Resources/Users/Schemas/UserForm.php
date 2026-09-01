<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('phone')->tel()->label('Telepon'),
                    Select::make('user_type')
                        ->label('Tipe')
                        ->options([
                            'admin' => 'Admin',
                            'vendor' => 'Vendor',
                            'couple' => 'Couple / Customer',
                        ])
                        ->required()
                        ->default('couple'),
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                            'inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('active'),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->label('Password'),
                ]),
        ]);
    }
}
