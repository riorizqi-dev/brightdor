<?php

namespace App\Filament\Resources\InvitationTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InvitationTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Template')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Kategori')->badge(),
                TextColumn::make('price')->money('IDR')->sortable(),
                IconColumn::make('is_premium')->boolean()->label('Premium'),
                IconColumn::make('is_featured')->boolean()->label('Featured'),
                IconColumn::make('is_active')->boolean()->label('Aktif'),
                TextColumn::make('sales_count')->label('Terjual')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_premium'),
                TernaryFilter::make('is_featured'),
                TernaryFilter::make('is_active'),
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
