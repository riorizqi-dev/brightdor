<?php

namespace App\Filament\Resources\InvitationOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvitationOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Undangan')
                ->columns(2)
                ->schema([
                    TextInput::make('order_code')->label('Kode Order')->disabled()->dehydrated(false)->placeholder('Auto'),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'active' => 'Active',
                            'expired' => 'Expired',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('invitation_template_id')
                        ->label('Template')
                        ->relationship('template', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('bride_name')->label('Nama Mempelai Wanita'),
                    TextInput::make('groom_name')->label('Nama Mempelai Pria'),
                    DatePicker::make('wedding_date')->label('Tanggal Nikah'),
                    TextInput::make('wedding_venue')->label('Venue'),
                    TextInput::make('subdomain')->label('Subdomain')->unique(ignoreRecord: true),
                    TextInput::make('custom_domain')->label('Custom Domain')->unique(ignoreRecord: true),
                    TextInput::make('price')->numeric()->prefix('Rp')->required(),
                ]),
        ]);
    }
}
