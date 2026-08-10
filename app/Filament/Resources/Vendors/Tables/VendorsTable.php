<?php

namespace App\Filament\Resources\Vendors\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')
                    ->label('Nama Usaha')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('city')
                    ->label('Kota')
                    ->toggleable(),
                TextColumn::make('rating_avg')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1) . ' ★'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'suspended' => 'gray',
                        default => 'gray',
                    }),
                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Daftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ]),
                SelectFilter::make('vendor_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'approved',
                            'is_verified' => true,
                            'verified_at' => now(),
                            'rejection_reason' => null,
                        ]);
                        Notification::make()->title('Vendor disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->form([
                        Textarea::make('rejection_reason')->label('Alasan')->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'is_verified' => false,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        Notification::make()->title('Vendor ditolak')->danger()->send();
                    }),
                Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'approved')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update(['status' => 'suspended']);
                        Notification::make()->title('Vendor di-suspend')->warning()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
