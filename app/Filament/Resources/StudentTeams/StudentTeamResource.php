<?php

namespace App\Filament\Resources\StudentTeams;

use App\Filament\Resources\StudentTeams\Pages\CreateStudentTeam;
use App\Filament\Resources\StudentTeams\Pages\EditStudentTeam;
use App\Filament\Resources\StudentTeams\Pages\ListStudentTeams;
use App\Filament\Resources\StudentTeams\Schemas\StudentTeamForm;
use App\Filament\Resources\StudentTeams\Tables\StudentTeamsTable;
use App\Models\StudentTeam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentTeamResource extends Resource
{
    protected static ?string $model = StudentTeam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Students / Teams';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StudentTeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentTeamsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentTeams::route('/'),
            'create' => CreateStudentTeam::route('/create'),
            'edit' => EditStudentTeam::route('/{record}/edit'),
        ];
    }
}
