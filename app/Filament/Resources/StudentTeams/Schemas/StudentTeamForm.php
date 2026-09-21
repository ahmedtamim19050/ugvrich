<?php

namespace App\Filament\Resources\StudentTeams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentTeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Team')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Team name')->required()->maxLength(190),

                        Select::make('department')->native(false)->searchable()->options(config('rich.departments')),

                        TextInput::make('leader_name')->label('Team leader')->maxLength(190),
                        TextInput::make('leader_email')->label('Leader email')->email()->maxLength(190),
                        TextInput::make('leader_phone')->label('Leader phone')->tel()->maxLength(40),

                        Select::make('supervisor_id')
                            ->label('Faculty supervisor')
                            ->relationship('supervisor', 'name')
                            ->searchable()
                            ->preload(),

                        TagsInput::make('members')
                            ->label('Members')
                            ->placeholder('Add a member and press Enter')
                            ->columnSpanFull(),

                        Select::make('project_id')
                            ->label('Working on')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        Textarea::make('notes')->rows(3)->columnSpanFull(),

                        Toggle::make('is_active')->label('Active')->default(true),
                    ]),
            ]);
    }
}
