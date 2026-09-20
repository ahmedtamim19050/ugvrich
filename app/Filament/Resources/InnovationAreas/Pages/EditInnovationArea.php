<?php

namespace App\Filament\Resources\InnovationAreas\Pages;

use App\Filament\Resources\InnovationAreas\InnovationAreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInnovationArea extends EditRecord
{
    protected static string $resource = InnovationAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
