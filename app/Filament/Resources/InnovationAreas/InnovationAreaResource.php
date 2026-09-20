<?php

namespace App\Filament\Resources\InnovationAreas;

use App\Filament\Resources\InnovationAreas\Pages\CreateInnovationArea;
use App\Filament\Resources\InnovationAreas\Pages\EditInnovationArea;
use App\Filament\Resources\InnovationAreas\Pages\ListInnovationAreas;
use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryForm;
use App\Models\InnovationArea;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class InnovationAreaResource extends Resource
{
    protected static ?string $model = InnovationArea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static string|\UnitEnum|null $navigationGroup = 'Innovation';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Innovation area';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Innovation Wing area')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(150)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')->required()->maxLength(150)->unique(ignoreRecord: true),

                    Select::make('department')
                        ->options(config('rich.departments'))
                        ->native(false)
                        ->searchable(),

                    Select::make('icon')
                        ->options(ServiceCategoryForm::ICONS)
                        ->native(false)
                        ->searchable(),

                    Textarea::make('description')->rows(3)->columnSpanFull(),

                    TagsInput::make('focus')
                        ->label('Focus areas')
                        ->placeholder('Add a focus area and press Enter')
                        ->columnSpanFull(),

                    FileUpload::make('image')
                        ->label('Cover image (optional)')
                        ->image()
                        ->disk('public')
                        ->directory('innovation')
                        ->imageEditor(),

                    TextInput::make('sort_order')->numeric()->default(0)->required(),

                    Toggle::make('is_active')->label('Visible on the site')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold')->wrap(),
                TextColumn::make('department')->badge()->color('primary'),
                TextColumn::make('projects_count')->counts('projects')->label('Projects'),
                IconColumn::make('is_active')->label('Visible')->boolean(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInnovationAreas::route('/'),
            'create' => CreateInnovationArea::route('/create'),
            'edit' => EditInnovationArea::route('/{record}/edit'),
        ];
    }
}
