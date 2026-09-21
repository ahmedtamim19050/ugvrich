<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold'),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('created_at')->label('Added')->since()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                // Nobody can delete their own account from here and lock themselves out.
                DeleteAction::make()->hidden(fn ($record) => $record->is(auth()->user())),
            ]);
    }
}
