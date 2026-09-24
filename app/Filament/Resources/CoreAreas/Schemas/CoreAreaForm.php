<?php

namespace App\Filament\Resources\CoreAreas\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\CoreArea;
use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryForm;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CoreAreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Bilingual::tabs(CoreArea::class, [
                    Section::make('Core area')
                        ->description('The four pillars shown on the homepage and About page: Research, Innovation, Consultancy and Hub.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(120)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(120)
                                ->unique(ignoreRecord: true),

                            TextInput::make('tagline')->maxLength(150),

                            Select::make('icon')
                                ->options(ServiceCategoryForm::ICONS)
                                ->native(false)
                                ->searchable(),

                            Textarea::make('description')
                                ->rows(4)
                                ->columnSpanFull(),

                            FileUpload::make('image')
                                ->label('Cover photo')
                                ->image()
                                ->disk('public')
                                ->directory('core-areas')
                                ->imageEditor()
                                ->columnSpanFull()
                                ->helperText('Shown at the top of the pillar card. Landscape, ideally 16:9. Leave empty to use the bundled photo for this pillar.'),

                            TagsInput::make('items')
                                ->label('Bullet points')
                                ->placeholder('Add a point and press Enter')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Display')
                        ->columns(2)
                        ->schema([
                            TextInput::make('sort_order')->numeric()->default(0)->required(),
                            Toggle::make('is_active')->label('Visible on the site')->default(true),
                        ]),
                ]),
            ]);
    }
}
