<?php

namespace App\Filament\Resources\Facilities\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FacilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Facility')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(190)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set, $get) => $get('slug') ? null : $set('slug', Str::slug($state))),

                        TextInput::make('slug')->required()->maxLength(190)->unique(ignoreRecord: true),

                        Select::make('department')
                            ->native(false)
                            ->searchable()
                            ->options(config('rich.departments')),

                        TextInput::make('location')->maxLength(150)->placeholder('e.g. Building A, Level 2'),

                        Textarea::make('description')->rows(3)->columnSpanFull(),

                        TagsInput::make('equipment')
                            ->placeholder('Add an instrument and press Enter')
                            ->columnSpanFull(),

                        TagsInput::make('services')
                            ->label('Available for')
                            ->placeholder('Add a use and press Enter')
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('facilities')
                            ->helperText('A landscape photo of the lab in use works best.')
                            ->columnSpanFull(),

                        TextInput::make('icon')->placeholder('beaker')->helperText('Icon name from the site icon set.'),
                        TextInput::make('sort_order')->numeric()->default(0)->required(),

                        Toggle::make('is_bookable')->label('Open to external work')->default(true),
                        Toggle::make('is_active')->label('Visible on the site')->default(true),
                    ]),
            ]);
    }
}
