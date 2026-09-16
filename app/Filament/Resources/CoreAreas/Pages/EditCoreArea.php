<?php

namespace App\Filament\Resources\CoreAreas\Pages;

use App\Filament\Resources\CoreAreas\CoreAreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCoreArea extends EditRecord
{
    protected static string $resource = CoreAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
