<?php

namespace App\Filament\Resources\Publications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('semibold')
                    ->wrap()
                    ->description(fn ($record) => $record->authors),

                TextColumn::make('venue')->searchable()->wrap()->toggleable(),

                TextColumn::make('year')->sortable(),

                TextColumn::make('kind')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'funded-project' ? 'Funded project' : 'Publication')
                    ->color(fn (string $state) => $state === 'funded-project' ? 'success' : 'primary'),

                IconColumn::make('is_active')->label('Visible')->boolean(),
            ])
            ->filters([
                SelectFilter::make('kind')->options([
                    'publication' => 'Publication',
                    'funded-project' => 'Funded project',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
