<?php

namespace App\Filament\Resources\ConsultancyRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConsultancyRequestsTable
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
                    ->description(fn ($record) => $record->designation),

                TextColumn::make('organization')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name')
                    ->label('Area')
                    ->badge()
                    ->color('gray')
                    ->placeholder('--'),

                TextColumn::make('area_of_interest')
                    ->label('Service')
                    ->toggleable()
                    ->placeholder('--')
                    ->limit(30),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str($state)->replace('_', ' ')->title())
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'in_review' => 'info',
                        'proposal_sent' => 'primary',
                        'accepted' => 'success',
                        'declined', 'closed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('document')
                    ->label('File')
                    ->badge()
                    ->formatStateUsing(fn ($state) => filled($state) ? 'Attached' : '--')
                    ->color(fn ($state) => filled($state) ? 'success' : 'gray')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'in_review' => 'In review',
                        'proposal_sent' => 'Proposal sent',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'closed' => 'Closed',
                    ]),

                SelectFilter::make('service_category_id')
                    ->label('Area of consultancy')
                    ->relationship('category', 'name')
                    ->preload(),

                Filter::make('has_document')
                    ->label('Has attachment')
                    ->query(fn ($query) => $query->whereNotNull('document')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
