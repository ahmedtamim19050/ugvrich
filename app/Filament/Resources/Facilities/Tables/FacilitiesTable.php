<?php

namespace App\Filament\Resources\Facilities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FacilitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image')->disk('public')->label('')->width(72)->height(44),
                TextColumn::make('name')->searchable()->weight('semibold')->description(fn ($record) => $record->location),
                TextColumn::make('department')->badge()->color('gray')->placeholder('--'),
                TextColumn::make('equipment')->label('Instruments')->formatStateUsing(fn ($state, $record) => count($record->equipment ?? [])),
                IconColumn::make('is_bookable')->label('External work')->boolean(),
                IconColumn::make('is_active')->label('Visible')->boolean(),
            ])
            ->filters([
                SelectFilter::make('department')->options(config('rich.departments')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
