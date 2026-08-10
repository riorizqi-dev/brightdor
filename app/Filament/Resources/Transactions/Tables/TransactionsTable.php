<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_code')->label('Kode')->searchable()->copyable()->weight('bold'),
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('amount')->money('IDR')->sortable(),
                TextColumn::make('net_amount')->money('IDR')->toggleable(),
                TextColumn::make('payment_gateway')->label('Gateway')->badge()->color('gray'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed', 'expired' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('paid_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                    'refunded' => 'Refunded',
                ]),
                SelectFilter::make('type')->options([
                    'payment' => 'Payment',
                    'refund' => 'Refund',
                    'commission' => 'Commission',
                    'payout' => 'Payout',
                ]),
                SelectFilter::make('payment_gateway')->options([
                    'midtrans' => 'Midtrans',
                    'xendit' => 'Xendit',
                    'manual' => 'Manual',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
