<?php

namespace App\Filament\Resources\InnovationAreas\Pages;

use App\Filament\Resources\InnovationAreas\InnovationAreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInnovationAreas extends ListRecords
{
    protected static string $resource = InnovationAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
