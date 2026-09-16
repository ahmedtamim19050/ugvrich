<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(150),
                        TextInput::make('designation')->maxLength(150),
                        TextInput::make('organization')->maxLength(180),

                        Select::make('rating')
                            ->required()
                            ->default(5)
                            ->native(false)
                            ->options([5 => '5 stars', 4 => '4 stars', 3 => '3 stars', 2 => '2 stars', 1 => '1 star']),

                        Textarea::make('quote')->required()->rows(5)->columnSpanFull(),

                        FileUpload::make('photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('testimonials'),

                        TextInput::make('sort_order')->numeric()->default(0)->required(),

                        Toggle::make('is_active')->label('Visible on the site')->default(true),
                    ]),
            ]);
    }
}
