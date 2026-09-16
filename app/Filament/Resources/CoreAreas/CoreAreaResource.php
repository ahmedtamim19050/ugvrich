<?php

namespace App\Filament\Resources\CoreAreas;

use App\Filament\Resources\CoreAreas\Pages\CreateCoreArea;
use App\Filament\Resources\CoreAreas\Pages\EditCoreArea;
use App\Filament\Resources\CoreAreas\Pages\ListCoreAreas;
use App\Filament\Resources\CoreAreas\Schemas\CoreAreaForm;
use App\Filament\Resources\CoreAreas\Tables\CoreAreasTable;
use App\Models\CoreArea;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CoreAreaResource extends Resource
{
    protected static ?string $model = CoreArea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Services';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Core Area';

    public static function form(Schema $schema): Schema
    {
        return CoreAreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoreAreasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoreAreas::route('/'),
            'create' => CreateCoreArea::route('/create'),
            'edit' => EditCoreArea::route('/{record}/edit'),
        ];
    }
}
