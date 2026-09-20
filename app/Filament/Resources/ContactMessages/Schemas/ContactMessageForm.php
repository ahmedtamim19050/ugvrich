<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->disabled(),
                        TextInput::make('email')->email()->disabled(),
                        TextInput::make('phone')->disabled(),
                        TextInput::make('organization')->disabled(),
                        TextInput::make('subject')->disabled()->columnSpanFull(),
                        Textarea::make('message')->rows(8)->disabled()->columnSpanFull(),
                    ]),

                Section::make('Handling')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->required()
                            ->default('new')
                            ->native(false)
                            ->options([
                                'new' => 'New',
                                'in_review' => 'In review',
                                'replied' => 'Replied',
                                'closed' => 'Closed',
                            ]),

                        DateTimePicker::make('handled_at')->label('Handled at')->seconds(false),

                        Textarea::make('admin_notes')->label('Internal notes')->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
