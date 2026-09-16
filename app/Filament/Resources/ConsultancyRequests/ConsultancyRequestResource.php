<?php

namespace App\Filament\Resources\ConsultancyRequests;

use App\Filament\Resources\ConsultancyRequests\Pages\CreateConsultancyRequest;
use App\Filament\Resources\ConsultancyRequests\Pages\EditConsultancyRequest;
use App\Filament\Resources\ConsultancyRequests\Pages\ListConsultancyRequests;
use App\Filament\Resources\ConsultancyRequests\Schemas\ConsultancyRequestForm;
use App\Filament\Resources\ConsultancyRequests\Tables\ConsultancyRequestsTable;
use App\Models\ConsultancyRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultancyRequestResource extends Resource
{
    protected static ?string $model = ConsultancyRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|\UnitEnum|null $navigationGroup = 'Requests';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Consultancy Request';

    public static function getNavigationBadge(): ?string
    {
        $new = static::getModel()::where('status', 'new')->count();

        return $new > 0 ? (string) $new : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return ConsultancyRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultancyRequestsTable::configure($table);
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
            'index' => ListConsultancyRequests::route('/'),
            'create' => CreateConsultancyRequest::route('/create'),
            'edit' => EditConsultancyRequest::route('/{record}/edit'),
        ];
    }
}
