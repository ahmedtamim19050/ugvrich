<?php

namespace App\Filament\Resources\ConsultancyRequests\Pages;

use App\Filament\Resources\ConsultancyRequests\ConsultancyRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsultancyRequests extends ListRecords
{
    protected static string $resource = ConsultancyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
