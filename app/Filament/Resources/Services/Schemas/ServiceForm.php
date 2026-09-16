<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service')
                    ->columns(2)
                    ->schema([
                        Select::make('service_category_id')
                            ->label('Area of consultancy')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')->required()->maxLength(150),

                        Textarea::make('description')->rows(3)->columnSpanFull(),

                        TextInput::make('sort_order')->numeric()->default(0)->required(),

                        Toggle::make('is_active')->label('Visible on the site')->default(true),
                    ]),
            ]);
    }
}
