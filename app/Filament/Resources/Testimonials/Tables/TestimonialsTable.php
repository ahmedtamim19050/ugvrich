<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('photo')->label('')->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn ($record) => trim($record->designation.($record->organization ? ', '.$record->organization : ''), ', ')),

                TextColumn::make('quote')
                    ->formatStateUsing(fn ($state) => Str::limit($state, 90))
                    ->wrap(),

                TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('*', (int) $state))
                    ->badge()
                    ->color('warning'),

                IconColumn::make('is_active')->label('Visible')->boolean(),
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
