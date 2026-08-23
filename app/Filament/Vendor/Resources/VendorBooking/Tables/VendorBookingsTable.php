<?php

namespace App\Filament\Vendor\Resources\VendorBooking\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VendorBookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Couple')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Paket')
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('event_date')
                    ->label('Tanggal Acara')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('event_time')
                    ->label('Waktu')
                    ->time('H:i'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'on_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'secondary' => 'refund',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Terkonfirmasi',
                        'on_progress' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'refund' => 'Refund',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'confirmed' => 'Terkonfirmasi',
                        'on_progress' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'refund' => 'Refund',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                ActionGroup::make([
                    Action::make('confirm')
                        ->label('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Booking')
                        ->modalDescription('Yakin ingin mengkonfirmasi booking ini?')
                        ->visible(fn (Model $record): bool => $record->status === 'pending')
                        ->action(function (Model $record): void {
                            $record->forceFill([
                                'status' => 'confirmed',
                                'confirmed_at' => now(),
                            ])->save();
                            Notification::make()
                                ->title('Booking dikonfirmasi')
                                ->success()
                                ->send();
                        }),
                    Action::make('start')
                        ->label('Mulai Acara')
                        ->icon('heroicon-o-play-circle')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->visible(fn (Model $record): bool => $record->status === 'confirmed')
                        ->action(function (Model $record): void {
                            $record->forceFill([
                                'status' => 'on_progress',
                            ])->save();
                            Notification::make()
                                ->title('Acara dimulai')
                                ->success()
                                ->send();
                        }),
                    Action::make('complete')
                        ->label('Selesai')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Model $record): bool => $record->status === 'on_progress')
                        ->action(function (Model $record): void {
                            $record->forceFill([
                                'status' => 'completed',
                                'completed_at' => now(),
                            ])->save();
                            Notification::make()
                                ->title('Booking selesai')
                                ->success()
                                ->send();
                        }),
                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Booking')
                        ->modalDescription('Yakin ingin menolak booking ini? Alasan akan dikirim ke customer.')
                        ->form([
                            \Filament\Forms\Components\Textarea::make('cancellation_reason')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(3),
                        ])
                        ->visible(fn (Model $record): bool => in_array($record->status, ['pending', 'confirmed']))
                        ->action(function (Model $record, array $data): void {
                            $record->forceFill([
                                'status' => 'cancelled',
                                'cancelled_at' => now(),
                                'cancellation_reason' => $data['cancellation_reason'],
                            ])->save();
                            Notification::make()
                                ->title('Booking ditolak')
                                ->warning()
                                ->send();
                        }),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button(),
            ]);
    }
}