<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Booking;
use App\Models\Transaction;
use App\Services\PaymentService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_code')->label('Kode')->searchable()->copyable()->weight('bold'),
                TextColumn::make('payable_booking_code')
                    ->label('Booking')
                    ->state(fn (Model $record): string => $record->payable instanceof Booking
                        ? $record->payable->booking_code
                        : '-')
                    ->toggleable(),
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('amount')->money('IDR')->sortable(),
                TextColumn::make('net_amount')->money('IDR')->toggleable(),
                ImageColumn::make('payment_proof')->label('Bukti')->toggleable(),
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
                Action::make('validasi')
                    ->label('Validasi Pembayaran')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Pembayaran')
                    ->modalDescription(fn (Transaction $record): string => 'Pastikan pembayaran '
                        . $record->transaction_code . ' sebesar '
                        . rupiah((float) $record->amount) . ' sudah diterima. Booking akan otomatis terkonfirmasi.')
                    ->visible(fn (Transaction $record): bool => $record->type === 'payment' && $record->status === 'pending')
                    ->action(function (Transaction $record): void {
                        PaymentService::markAsPaid($record);
                        Notification::make()
                            ->title('Pembayaran divalidasi, booking terkonfirmasi')
                            ->success()
                            ->send();
                    }),
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription(fn (Transaction $record): string => 'Yakin menolak pembayaran '
                        . $record->transaction_code . '?')
                    ->visible(fn (Transaction $record): bool => $record->type === 'payment' && $record->status === 'pending')
                    ->action(function (Transaction $record): void {
                        PaymentService::markAsFailed($record);
                        Notification::make()
                            ->title('Pembayaran ditolak')
                            ->warning()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}