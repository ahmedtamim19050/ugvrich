<?php

namespace App\Filament\Resources\Partners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('logo')->label('')->height(32)->width(64),

                TextColumn::make('name')->searchable()->weight('semibold'),

                TextColumn::make('type')->badge()->color('gray')->placeholder('--'),

                TextColumn::make('website')
                    ->url(fn ($record) => $record->website, shouldOpenInNewTab: true)
                    ->color('primary')
                    ->placeholder('--')
                    ->toggleable(),

                IconColumn::make('is_active')->label('Visible')->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->options(fn () => \App\Models\Partner::query()
                    ->whereNotNull('type')
                    ->distinct()
                    ->pluck('type', 'type')
                    ->all()),
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
