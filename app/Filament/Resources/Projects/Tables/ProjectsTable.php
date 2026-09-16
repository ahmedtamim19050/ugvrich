<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->height(40)->width(64),

                TextColumn::make('title')
                    ->searchable()
                    ->weight('semibold')
                    ->wrap()
                    ->description(fn ($record) => $record->client),

                TextColumn::make('category.name')
                    ->label('Area')
                    ->badge()
                    ->color('primary')
                    ->placeholder('--'),

                TextColumn::make('year')->sortable(),

                TextColumn::make('duration')->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'ongoing' => 'warning',
                        default => 'gray',
                    }),

                IconColumn::make('is_featured')->label('Featured')->boolean(),
            ])
            ->filters([
                SelectFilter::make('service_category_id')
                    ->label('Area of consultancy')
                    ->relationship('category', 'name')
                    ->preload(),

                SelectFilter::make('status')->options([
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'planned' => 'Planned',
                ]),

                TernaryFilter::make('is_featured')->label('Featured on homepage'),
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
