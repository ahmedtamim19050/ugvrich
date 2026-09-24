<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Bilingual::tabs(Project::class, [
                    Tabs::make()->columnSpanFull()->tabs([

                        Tabs\Tab::make('Overview')->icon('heroicon-o-identification')->schema([
                            Section::make()->columns(2)->schema([
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

                                TextInput::make('code')
                                    ->label('Project ID')
                                    ->maxLength(40)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Generated automatically, e.g. RICH-CSE-2026-001')
                                    ->helperText('Leave empty to generate from the department and start year.'),

                                Select::make('type')
                                    ->required()
                                    ->default('innovation')
                                    ->native(false)
                                    ->live()
                                    ->options(config('rich.project_types')),

                                Select::make('department')
                                    ->options(config('rich.departments'))
                                    ->native(false)
                                    ->searchable(),

                                Select::make('innovation_area_id')
                                    ->label('Innovation Wing area')
                                    ->relationship('innovationArea', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('service_category_id')
                                    ->label('Area of consultancy')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('client')
                                    ->label('Client / Partner')
                                    ->maxLength(150),

                                TextInput::make('year')
                                    ->numeric()
                                    ->minValue(1990)
                                    ->maxValue((int) date('Y') + 5),

                                Select::make('status')
                                    ->required()
                                    ->default('ongoing')
                                    ->native(false)
                                    ->options([
                                        'ongoing' => 'Ongoing',
                                        'completed' => 'Completed',
                                        'planned' => 'Planned',
                                    ]),

                                Toggle::make('is_featured')->label('Feature on the homepage'),
                            ]),
                        ]),

                        Tabs\Tab::make('Team & management')->icon('heroicon-o-users')->schema([
                            Section::make('Team')->columns(2)->schema([
                                TextInput::make('lead_name')
                                    ->label('PI / Team leader')
                                    ->maxLength(150),

                                TagsInput::make('team_members')
                                    ->label('Team members')
                                    ->placeholder('Add a name and press Enter'),
                            ]),

                            Section::make('Schedule & budget')->columns(4)->schema([
                                TextInput::make('budget')
                                    ->numeric()
                                    ->prefix('BDT')
                                    ->minValue(0),

                                DatePicker::make('start_date')->native(false),
                                DatePicker::make('deadline')->native(false)->afterOrEqual('start_date'),

                                TextInput::make('duration')
                                    ->maxLength(80)
                                    ->placeholder('8 months'),

                                TextInput::make('progress')
                                    ->label('Progress')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->default(0),
                            ]),

                            Section::make('Pipeline & IP')->columns(3)->schema([
                                Select::make('stage')
                                    ->label('Current stage')
                                    ->options(config('rich.pipeline_stages'))
                                    ->native(false),

                                Select::make('patent_status')
                                    ->label('Patent / IP status')
                                    ->options(config('rich.patent_statuses'))
                                    ->default('none')
                                    ->native(false),

                                Select::make('commercialization_status')
                                    ->label('Commercialization status')
                                    ->options(config('rich.commercialization_statuses'))
                                    ->default('none')
                                    ->native(false),
                            ]),
                        ]),

                        Tabs\Tab::make('Innovation story')->icon('heroicon-o-light-bulb')->schema([
                            Section::make()
                                ->description('The sections of the public project page, in order: Problem → Solution → Technology → Research → Patent/IP → Commercial potential.')
                                ->schema([
                                    Textarea::make('problem')->rows(3),
                                    Textarea::make('solution')->rows(3),
                                    TagsInput::make('technologies')
                                        ->label('Technology')
                                        ->placeholder('Add a technology and press Enter'),
                                    Textarea::make('research_summary')->label('Research')->rows(3),
                                    Textarea::make('patent_details')->label('Patent / IP details')->rows(3),
                                    Textarea::make('commercial_potential')->rows(3),
                                ]),
                        ]),

                        Tabs\Tab::make('Content')->icon('heroicon-o-document-text')->schema([
                            Section::make()->schema([
                                Textarea::make('summary')
                                    ->rows(2)
                                    ->maxLength(400)
                                    ->helperText('Short line shown on project cards.'),

                                Textarea::make('description')->rows(8),

                                Textarea::make('outcome')
                                    ->label('Project outcome')
                                    ->rows(4),
                            ]),
                        ]),

                        Tabs\Tab::make('Media')->icon('heroicon-o-photo')->schema([
                            Section::make()->columns(3)->schema([
                                FileUpload::make('image')
                                    ->label('Cover image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('projects')
                                    ->imageEditor()
                                    ->columnSpan(2),

                                TextInput::make('sort_order')->numeric()->default(0)->required(),

                                FileUpload::make('gallery')
                                    ->label('Photos')
                                    ->multiple()
                                    ->image()
                                    ->reorderable()
                                    ->disk('public')
                                    ->directory('projects/gallery')
                                    ->columnSpanFull(),

                                TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->url()
                                    ->placeholder('https://www.youtube.com/watch?v=…')
                                    ->columnSpanFull(),
                            ]),
                        ]),
                    ]),
                ]),
            ]);
    }
}
