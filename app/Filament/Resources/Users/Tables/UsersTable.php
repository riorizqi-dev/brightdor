<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('phone')->label('Telepon')->toggleable(),
                TextColumn::make('user_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'vendor' => 'primary',
                        'couple' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'inactive' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Daftar')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user_type')->options([
                    'admin' => 'Admin',
                    'vendor' => 'Vendor',
                    'couple' => 'Couple',
                ]),
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'suspended' => 'Suspended',
                    'inactive' => 'Inactive',
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
