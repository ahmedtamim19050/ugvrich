<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dashboard user')
                    ->description('Everyone listed here can sign in to the management dashboard.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(190),
                        TextInput::make('email')->email()->required()->maxLength(190)->unique(ignoreRecord: true),

                        // Blank on edit keeps the current password.
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave blank to keep the current password.' : null),
                    ]),
            ]);
    }
}
