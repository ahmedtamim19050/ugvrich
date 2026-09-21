<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ConsultancyRequests\ConsultancyRequestResource;
use App\Filament\Resources\IdeaSubmissions\IdeaSubmissionResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Publications\PublicationResource;
use App\Models\ConsultancyRequest;
use App\Models\IdeaSubmission;
use App\Models\Post;
use App\Models\Project;
use App\Models\Publication;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * The management dashboard home: headline counts, the innovation pipeline,
 * where each flagship project stands, and what needs attention next.
 */
class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    public function getHeading(): string
    {
        return 'UGV RICH';
    }

    public function getSubheading(): ?string
    {
        return 'Research & Innovation Management System';
    }

    /** @return array<string, mixed> */
    public function getData(): array
    {
        $projects = Project::query()->get(['id', 'title', 'slug', 'type', 'status', 'stage', 'patent_status', 'commercialization_status', 'progress', 'department']);
        $ideas = IdeaSubmission::query()->get(['id', 'stage', 'status']);

        $url = fn (string $resource, array $filters = []) => $resource::getUrl('index', $filters ? ['filters' => $filters] : []);

        $kpis = [
            ['Active Projects', $projects->where('status', 'ongoing')->count(), 'briefcase', 'green',
                $url(ProjectResource::class, ['status' => ['value' => 'ongoing']])],
            ['New Ideas', $ideas->where('status', 'new')->count(), 'light-bulb', 'amber',
                $url(IdeaSubmissionResource::class, ['status' => ['value' => 'new']])],
            ['Prototypes', $projects->where('stage', 'prototype')->count(), 'cog', 'blue',
                $url(ProjectResource::class, ['stage' => ['value' => 'prototype']])],
            ['Patent / IP', $projects->where('patent_status', '!=', 'none')->count(), 'key', 'violet',
                $url(ProjectResource::class, ['ip' => ['value' => 'protected']])],
            ['Startups', $projects->whereIn('commercialization_status', ['incubating', 'startup', 'market'])->count()
                + $ideas->whereIn('stage', ['startup', 'market'])->count(), 'rocket', 'navy',
                $url(ProjectResource::class, ['commercialization_status' => ['value' => 'startup']])],
            ['Publications', Publication::where('is_active', true)->where('kind', '!=', 'funded-project')->count(), 'document-text', 'slate',
                $url(PublicationResource::class)],
        ];

        // The pipeline as the management team talks about it. Submitted ideas
        // feed the first two stages; projects carry the rest.
        $at = fn (string ...$stages) => $projects->whereIn('stage', $stages)->count();
        $pipeline = [
            ['Ideas', $ideas->where('stage', 'idea')->count() + $at('idea'), 'Submitted and waiting for review'],
            ['Evaluation', $ideas->where('stage', 'evaluation')->count() + $at('selected'), 'Reviewed for originality and feasibility'],
            ['Research', $at('research'), 'Studying the problem and the approach'],
            ['Prototype', $at('prototype') + $ideas->where('stage', 'prototype')->count(), 'A working model being built'],
            ['Testing', $at('testing'), 'Proving it in real conditions'],
            ['Patent / IP', $at('patent'), 'Protection being filed'],
            ['Commercialization', $at('incubation', 'commercialization'), 'Preparing for market'],
            ['Startup', $projects->where('commercialization_status', 'startup')->count() + $ideas->where('stage', 'startup')->count(), 'A venture has been formed'],
        ];

        return [
            'kpis' => $kpis,
            'pipeline' => $pipeline,
            'pipelineMax' => max(1, max(array_column($pipeline, 1))),
            'projects' => $projects->where('type', 'innovation')->sortBy(fn ($p) => $p->title)->values(),
            'events' => Post::published()->events()->where('event_at', '>=', now()->startOfDay())->orderBy('event_at')->take(4)->get(),
            'publications' => Publication::where('is_active', true)->where('kind', '!=', 'funded-project')->orderByDesc('year')->take(4)->get(),
            'funding' => Publication::where('is_active', true)->where('kind', 'funded-project')->orderByDesc('year')->take(4)->get(),
            'requests' => ConsultancyRequest::with('category')->latest()->take(5)->get(),
            'links' => [
                'projects' => $url(ProjectResource::class, ['type' => ['value' => 'innovation']]),
                'events' => $url(PostResource::class, ['type' => ['value' => 'event']]),
                'publications' => $url(PublicationResource::class),
                'funding' => $url(PublicationResource::class, ['kind' => ['value' => 'funded-project']]),
                'requests' => $url(ConsultancyRequestResource::class),
            ],
        ];
    }
}
