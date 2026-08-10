<?php

namespace App\Filament\Resources\InvitationOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvitationOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_code')->label('Kode')->searchable()->copyable()->weight('bold'),
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('template.name')->label('Template'),
                TextColumn::make('groom_name')->label('Pria')->toggleable(),
                TextColumn::make('bride_name')->label('Wanita')->toggleable(),
                TextColumn::make('subdomain')->label('Subdomain'),
                TextColumn::make('price')->money('IDR'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid', 'active' => 'success',
                        'expired' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'active' => 'Active',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
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
