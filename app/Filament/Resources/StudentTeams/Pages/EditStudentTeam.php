<?php

namespace App\Filament\Resources\StudentTeams\Pages;

use App\Filament\Resources\StudentTeams\StudentTeamResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentTeam extends EditRecord
{
    protected static string $resource = StudentTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
