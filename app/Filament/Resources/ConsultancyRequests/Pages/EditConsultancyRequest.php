<?php

namespace App\Filament\Resources\ConsultancyRequests\Pages;

use App\Filament\Resources\ConsultancyRequests\ConsultancyRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConsultancyRequest extends EditRecord
{
    protected static string $resource = ConsultancyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
