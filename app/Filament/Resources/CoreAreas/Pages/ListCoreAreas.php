<?php

namespace App\Filament\Resources\CoreAreas\Pages;

use App\Filament\Resources\CoreAreas\CoreAreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoreAreas extends ListRecords
{
    protected static string $resource = CoreAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
