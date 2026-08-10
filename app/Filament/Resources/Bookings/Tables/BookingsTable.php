<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('vendor.business_name')
                    ->label('Vendor')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Jasa')
                    ->toggleable()
                    ->limit(30),
                TextColumn::make('event_date')
                    ->label('Tanggal Acara')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'on_progress' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'gray',
                        'refund' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'on_progress' => 'On Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'refund' => 'Refund',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
