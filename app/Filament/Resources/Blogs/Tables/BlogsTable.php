<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(50)->weight('bold'),
                TextColumn::make('author.name')->label('Author'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                IconColumn::make('is_featured')->boolean()->label('Featured'),
                TextColumn::make('views_count')->label('Views'),
                TextColumn::make('published_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Published']),
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
