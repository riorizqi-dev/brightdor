<?php

namespace App\Filament\Resources\Invitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')->searchable()->weight('bold'),
                TextColumn::make('user.name')->label('Owner')->searchable(),
                TextColumn::make('template.name')->label('Template'),
                TextColumn::make('subdomain'),
                TextColumn::make('custom_domain')->toggleable(),
                TextColumn::make('views_count')->label('Views')->sortable(),
                TextColumn::make('rsvp_yes')->label('RSVP Ya'),
                TextColumn::make('rsvp_no')->label('RSVP Tidak'),
                IconColumn::make('is_published')->boolean()->label('Live'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
