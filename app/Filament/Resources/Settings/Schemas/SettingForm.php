<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting')
                    ->description('Raw editor. For everyday changes use Site > Site Settings instead.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit'),

                        Select::make('group')
                            ->required()
                            ->default('general')
                            ->native(false)
                            ->options([
                                'general' => 'General',
                                'hero' => 'Hero',
                                'contact' => 'Contact',
                                'social' => 'Social',
                            ]),

                        Select::make('type')
                            ->required()
                            ->default('text')
                            ->native(false)
                            ->options([
                                'text' => 'Text',
                                'json' => 'JSON list',
                            ]),

                        Textarea::make('value')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('JSON-typed settings must contain valid JSON.'),
                    ]),
            ]);
    }
}
