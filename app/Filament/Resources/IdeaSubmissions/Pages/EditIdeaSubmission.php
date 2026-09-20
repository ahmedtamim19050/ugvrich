<?php

namespace App\Filament\Resources\IdeaSubmissions\Pages;

use App\Filament\Resources\IdeaSubmissions\IdeaSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIdeaSubmission extends EditRecord
{
    protected static string $resource = IdeaSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
