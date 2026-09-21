<?php

namespace App\Filament\Resources\StudentTeams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentTeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->label('Team')->searchable()->weight('semibold')->description(fn ($record) => $record->leader_name),
                TextColumn::make('department')->badge()->color('gray')->placeholder('--'),
                TextColumn::make('members')->label('Members')->formatStateUsing(fn ($state, $record) => count($record->members ?? [])),
                TextColumn::make('supervisor.name')->label('Supervisor')->placeholder('--'),
                TextColumn::make('project.title')->label('Working on')->placeholder('--')->limit(30),
                IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('department')->options(config('rich.departments')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
