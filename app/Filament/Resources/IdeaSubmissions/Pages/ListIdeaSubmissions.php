<?php

namespace App\Filament\Resources\IdeaSubmissions\Pages;

use App\Filament\Resources\IdeaSubmissions\IdeaSubmissionResource;
use Filament\Resources\Pages\ListRecords;

class ListIdeaSubmissions extends ListRecords
{
    protected static string $resource = IdeaSubmissionResource::class;
}
