<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Withdrawal Vendor')
                ->columns(2)
                ->schema([
                    TextInput::make('payout_code')->disabled()->dehydrated(false)->placeholder('Auto'),
                    Select::make('vendor_id')
                        ->label('Vendor')
                        ->relationship('vendor', 'business_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('amount')->numeric()->prefix('Rp')->required(),
                    TextInput::make('fee')->numeric()->prefix('Rp')->default(0),
                    TextInput::make('net_amount')->numeric()->prefix('Rp')->required(),
                    Select::make('status')->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'paid' => 'Paid',
                        'rejected' => 'Rejected',
                    ])->default('pending')->required(),
                    TextInput::make('bank_name')->label('Bank'),
                    TextInput::make('bank_account_number')->label('No. Rekening'),
                    TextInput::make('bank_account_name')->label('Atas Nama')->columnSpanFull(),
                    Textarea::make('admin_notes')->label('Catatan Admin')->columnSpanFull(),
                ]),
        ]);
    }
}
