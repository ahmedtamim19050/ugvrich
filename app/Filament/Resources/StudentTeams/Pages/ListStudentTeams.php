<?php

namespace App\Filament\Resources\StudentTeams\Pages;

use App\Filament\Resources\StudentTeams\StudentTeamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentTeams extends ListRecords
{
    protected static string $resource = StudentTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
