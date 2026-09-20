<?php

namespace App\Filament\Resources\IdeaSubmissions;

use App\Filament\Resources\IdeaSubmissions\Pages\EditIdeaSubmission;
use App\Filament\Resources\IdeaSubmissions\Pages\ListIdeaSubmissions;
use App\Filament\Resources\IdeaSubmissions\Schemas\IdeaSubmissionForm;
use App\Filament\Resources\IdeaSubmissions\Tables\IdeaSubmissionsTable;
use App\Models\IdeaSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IdeaSubmissionResource extends Resource
{
    protected static ?string $model = IdeaSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static string|\UnitEnum|null $navigationGroup = 'Requests';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Idea Submission';

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
        return IdeaSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IdeaSubmissionsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        // Ideas only ever arrive through the public form.
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIdeaSubmissions::route('/'),
            'edit' => EditIdeaSubmission::route('/{record}/edit'),
        ];
    }
}
