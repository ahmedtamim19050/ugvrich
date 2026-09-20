<?php

namespace App\Filament\Resources\Publications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Research highlight')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),

                        TextInput::make('authors')
                            ->maxLength(255)
                            ->placeholder('M. Hasan, S. Rahman'),

                        TextInput::make('venue')
                            ->label('Journal / conference / funder')
                            ->maxLength(255),

                        Select::make('kind')
                            ->required()
                            ->default('journal')
                            ->native(false)
                            ->options(config('rich.publication_kinds')),

                        TextInput::make('year')
                            ->numeric()
                            ->minValue(1950)
                            ->maxValue((int) date('Y') + 5),

                        TextInput::make('doi')->label('DOI')->maxLength(120),

                        TextInput::make('url')->url()->maxLength(255),

                        Textarea::make('abstract')->rows(4)->columnSpanFull(),

                        TextInput::make('sort_order')->numeric()->default(0)->required(),

                        Toggle::make('is_active')->label('Visible on the site')->default(true),
                    ]),
            ]);
    }
}
