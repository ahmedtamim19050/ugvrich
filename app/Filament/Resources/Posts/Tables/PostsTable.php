<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')->label('')->height(40)->width(64),

                TextColumn::make('title')
                    ->searchable()
                    ->weight('semibold')
                    ->wrap()
                    ->description(fn ($record) => $record->category),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'event' ? 'success' : 'primary')
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                TextColumn::make('event_at')
                    ->label('Event date')
                    ->dateTime('j M Y, g:i A')
                    ->placeholder('--')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('j M Y')
                    ->placeholder('Draft')
                    ->badge()
                    ->color(fn ($state) => $state === null ? 'gray' : 'success')
                    ->sortable(),

                IconColumn::make('is_featured')->label('Featured')->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'news' => 'News',
                    'event' => 'Event',
                ]),

                TernaryFilter::make('is_featured')->label('Featured on homepage'),

                Filter::make('drafts')
                    ->label('Drafts only')
                    ->query(fn ($query) => $query->whereNull('published_at')),
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
