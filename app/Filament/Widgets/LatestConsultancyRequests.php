<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ConsultancyRequests\ConsultancyRequestResource;
use App\Models\ConsultancyRequest;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestConsultancyRequests extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest consultancy requests';

    public function table(Table $table): Table
    {
        return $table
            ->query(ConsultancyRequest::query()->with('category')->latest()->limit(8))
            ->paginated(false)
            ->emptyStateHeading('No requests yet')
            ->emptyStateDescription('Requests submitted through the public contact form will appear here.')
            ->columns([
                TextColumn::make('created_at')->label('Received')->since(),

                TextColumn::make('name')
                    ->weight('semibold')
                    ->description(fn ($record) => $record->organization),

                TextColumn::make('email')->copyable(),

                TextColumn::make('category.name')
                    ->label('Area')
                    ->badge()
                    ->color('gray')
                    ->placeholder('--'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str($state)->replace('_', ' ')->title())
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'in_review' => 'info',
                        'accepted' => 'success',
                        'declined', 'closed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn ($record) => ConsultancyRequestResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
