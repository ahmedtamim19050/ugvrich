<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at?->format('j F Y, g:i A'))
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn ($record) => $record->organization),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('subject')
                    ->searchable()
                    ->placeholder('--')
                    ->limit(40),

                TextColumn::make('message')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str($state)->replace('_', ' ')->title())
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'in_review' => 'info',
                        'replied' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'new' => 'New',
                    'in_review' => 'In review',
                    'replied' => 'Replied',
                    'closed' => 'Closed',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
