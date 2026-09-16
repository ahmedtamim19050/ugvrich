<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('group')
            ->defaultSort('key')
            ->columns([
                TextColumn::make('key')->searchable()->weight('semibold')->copyable(),

                TextColumn::make('value')
                    ->formatStateUsing(fn ($state) => Str::limit((string) $state, 110))
                    ->wrap()
                    ->searchable(),

                TextColumn::make('type')->badge()->color('gray'),

                TextColumn::make('group')->badge()->color('primary')->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')->options([
                    'general' => 'General',
                    'hero' => 'Hero',
                    'contact' => 'Contact',
                    'social' => 'Social',
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
