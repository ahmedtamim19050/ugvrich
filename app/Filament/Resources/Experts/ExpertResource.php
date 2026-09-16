<?php

namespace App\Filament\Resources\Experts;

use App\Filament\Resources\Experts\Pages\CreateExpert;
use App\Filament\Resources\Experts\Pages\EditExpert;
use App\Filament\Resources\Experts\Pages\ListExperts;
use App\Filament\Resources\Experts\Schemas\ExpertForm;
use App\Filament\Resources\Experts\Tables\ExpertsTable;
use App\Models\Expert;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpertResource extends Resource
{
    protected static ?string $model = Expert::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'People & Partners';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Expert';

    public static function form(Schema $schema): Schema
    {
        return ExpertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpertsTable::configure($table);
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
            'index' => ListExperts::route('/'),
            'create' => CreateExpert::route('/create'),
            'edit' => EditExpert::route('/{record}/edit'),
        ];
    }
}
