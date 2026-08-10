<?php

namespace App\Filament\Resources\Payouts\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PayoutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payout_code')->label('Kode')->searchable()->copyable()->weight('bold'),
                TextColumn::make('vendor.business_name')->label('Vendor')->searchable(),
                TextColumn::make('amount')->money('IDR')->sortable(),
                TextColumn::make('net_amount')->money('IDR')->label('Net'),
                TextColumn::make('bank_name')->label('Bank'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'paid' => 'Paid',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'processing'], true))
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'paid',
                            'processed_at' => now(),
                            'processed_by' => auth()->id(),
                        ]);
                        Notification::make()->title('Payout ditandai paid')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
