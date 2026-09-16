<?php

namespace App\Filament\Widgets;

use App\Models\ConsultancyRequest;
use App\Models\Expert;
use App\Models\Post;
use App\Models\Project;
use App\Models\ServiceCategory;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RichOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newRequests = ConsultancyRequest::where('status', 'new')->count();
        $thisMonth = ConsultancyRequest::whereBetween('created_at', [now()->startOfMonth(), now()])->count();

        return [
            Stat::make('New consultancy requests', $newRequests)
                ->description($thisMonth.' received this month')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($newRequests > 0 ? 'warning' : 'success'),

            Stat::make('Experts listed', Expert::where('is_active', true)->count())
                ->description(ServiceCategory::where('is_active', true)->count().' areas of consultancy')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Projects published', Project::count())
                ->description(Project::where('is_featured', true)->count().' featured on the homepage')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),

            Stat::make('News & events', Post::whereNotNull('published_at')->count())
                ->description(Post::whereNull('published_at')->count().' drafts')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('gray'),
        ];
    }
}
