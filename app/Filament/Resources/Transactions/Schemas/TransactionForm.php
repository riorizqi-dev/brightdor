<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Transaksi')
                ->columns(2)
                ->schema([
                    TextInput::make('transaction_code')->label('Kode')->disabled()->dehydrated(false)->placeholder('Auto'),
                    Select::make('type')->options([
                        'payment' => 'Payment',
                        'refund' => 'Refund',
                        'commission' => 'Commission',
                        'payout' => 'Payout',
                    ])->required()->default('payment'),
                    Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
                    Select::make('status')->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                        'refunded' => 'Refunded',
                    ])->required()->default('pending'),
                    TextInput::make('amount')->numeric()->prefix('Rp')->required(),
                    TextInput::make('fee')->numeric()->prefix('Rp')->default(0),
                    TextInput::make('net_amount')->numeric()->prefix('Rp')->default(0),
                    TextInput::make('payment_method')->label('Metode'),
                    Select::make('payment_gateway')->options([
                        'midtrans' => 'Midtrans',
                        'xendit' => 'Xendit',
                        'manual' => 'Manual',
                        'other' => 'Other',
                    ]),
                    TextInput::make('gateway_reference')->label('Gateway Ref'),
                ]),
        ]);
    }
}
