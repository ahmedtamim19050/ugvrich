<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(ignoreRecord: true),

                        Select::make('service_category_id')
                            ->label('Area of consultancy')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('client')
                            ->label('Client / Partner')
                            ->maxLength(150),

                        TextInput::make('duration')
                            ->maxLength(80)
                            ->placeholder('8 months'),

                        TextInput::make('year')
                            ->numeric()
                            ->minValue(1990)
                            ->maxValue((int) date('Y') + 5),

                        Select::make('status')
                            ->required()
                            ->default('completed')
                            ->native(false)
                            ->options([
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'planned' => 'Planned',
                            ]),
                    ]),

                Section::make('Content')
                    ->schema([
                        Textarea::make('summary')
                            ->rows(2)
                            ->maxLength(400)
                            ->helperText('Short line shown on project cards.')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(8)
                            ->columnSpanFull(),

                        Textarea::make('outcome')
                            ->label('Project outcome')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & display')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Cover image')
                            ->image()
                            ->disk('public')
                            ->directory('projects')
                            ->imageEditor()
                            ->columnSpan(2),

                        TextInput::make('sort_order')->numeric()->default(0)->required(),

                        FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->disk('public')
                            ->directory('projects/gallery')
                            ->columnSpanFull(),

                        Toggle::make('is_featured')->label('Feature on the homepage'),
                    ]),
            ]);
    }
}
