@php
    $isInnovation = $project->type === 'innovation';
    $commissionUrl = $isInnovation
        ? route('contact').'?intent=collaborate'
        : route('contact').($project->category ? '?area='.$project->category->slug : '');

    $stages = \App\Support\Vocabulary::all('pipeline_stages');
    $stageIcons = [
        'idea' => 'lightbulb', 'selected' => 'check', 'research' => 'beaker', 'prototype' => 'cog',
        'testing' => 'target', 'patent' => 'key', 'incubation' => 'rocket', 'commercialization' => 'briefcase',
    ];

    // YouTube / Vimeo links become an embeddable URL; anything else is shown as a link.
    $embed = null;
    if ($project->video_url && preg_match('~(?:youtube\.com/(?:watch\?v=|shorts/|embed/)|youtu\.be/)([\w-]{11})~', $project->video_url, $m)) {
        $embed = 'https://www.youtube-nocookie.com/embed/'.$m[1];
    } elseif ($project->video_url && preg_match('~vimeo\.com/(\d+)~', $project->video_url, $m)) {
        $embed = 'https://player.vimeo.com/video/'.$m[1];
    }

    $photos = collect([$project->image])->merge($project->gallery ?? [])->filter()->unique()->values();
    $team = collect($project->team_members ?? [])->filter();

    // The story, in the order the Innovation Wing presents it. Empty sections are skipped.
    $story = collect([
        ['problem', __('site.projects.problem'), 'target', $project->problem],
        ['solution', __('site.projects.solution'), 'lightbulb', $project->solution],
    ])->filter(fn ($s) => filled($s[3]));
@endphp

<x-layouts.app :title="$project->title" :description="$project->summary">

    <x-page-hero
        :eyebrow="$project->innovationArea?->name ?? $project->category?->name ?? $project->type_label"
        :title="$project->title"
        :lead="$project->summary"
        :image="$project->image"
        :breadcrumbs="[($isInnovation ? __('site.nav.innovation') : __('site.nav.projects')) => ($isInnovation ? route('innovation.index') : route('projects.index')), Str::limit($project->title, 40) => null]" />

    {{-- Stage tracker --}}
    @if ($project->stage)
        <section class="border-b border-ink-100 bg-ink-50 py-8">
            <div class="container-rich">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-700">{{ __('site.projects.current_stage') }}</p>
                    <p class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-3.5 py-1.5 text-[13px] font-semibold text-white">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span>
                        {{ __('site.projects.stage_of', ['label' => $project->stage_label, 'index' => $project->stage_index, 'total' => count($stages)]) }}
                    </p>
                </div>

                <ol class="mt-5 grid grid-cols-4 gap-2 sm:grid-cols-8">
                    @foreach ($stages as $key => $label)
                        @php
                            $n = $loop->iteration;
                            $state = $n < $project->stage_index ? 'done' : ($n === $project->stage_index ? 'current' : 'todo');
                        @endphp
                        <li class="flex flex-col items-center gap-2 text-center" @if ($state === 'current') aria-current="step" @endif>
                            <span class="h-1.5 w-full rounded-full {{ $state === 'todo' ? 'bg-ink-200' : 'bg-brand-600' }}"></span>
                            <span @class([
                                'flex h-9 w-9 items-center justify-center rounded-xl transition',
                                'bg-brand-600 text-white' => $state === 'done',
                                'bg-white text-brand-700 ring-4 ring-brand-200' => $state === 'current',
                                'bg-white text-ink-300 ring-1 ring-ink-200' => $state === 'todo',
                            ])>
                                @if ($state === 'done')
                                    <x-ui-icon name="check" class="h-4 w-4" stroke="2.6" />
                                @else
                                    <x-ui-icon :name="$stageIcons[$key] ?? 'check'" class="h-4 w-4" />
                                @endif
                            </span>
                            <span @class(['text-[11.5px] leading-tight', 'font-semibold text-ink-950' => $state !== 'todo', 'text-ink-400' => $state === 'todo'])>{{ $label }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    <section class="bg-white py-16 sm:py-20">
        <div class="container-rich grid gap-12 lg:grid-cols-[1.4fr_0.6fr] lg:gap-14">

            <article class="space-y-6">
                {{-- Problem → Solution --}}
                @if ($story->isNotEmpty())
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($story as [$id, $title, $icon, $text])
                            <div class="reveal relative overflow-hidden rounded-[1.75rem] border p-7 {{ $id === 'solution' ? 'border-brand-200 bg-brand-50' : 'border-ink-100 bg-white' }}">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $id === 'solution' ? 'bg-brand-600 text-white' : 'bg-ink-950 text-white' }}">
                                        <x-ui-icon :name="$icon" class="h-5 w-5" />
                                    </span>
                                    <h2 class="font-display text-xl font-bold text-ink-950">{{ $title }}</h2>
                                </div>
                                <p class="mt-4 text-[15.5px] leading-relaxed text-ink-700">{{ $text }}</p>
                                @if ($loop->first && $story->count() > 1)
                                    <span class="absolute -right-3 top-1/2 z-10 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white text-brand-600 shadow ring-1 ring-ink-100 sm:flex" aria-hidden="true">
                                        <x-ui-icon name="arrow-right" class="h-4 w-4" />
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Description (consultancy + extra detail) --}}
                @if ($project->description)
                    <div class="reveal rounded-[1.75rem] border border-ink-100 p-7">
                        <h2 class="font-display text-xl font-bold text-ink-950">{{ __('site.projects.about') }}</h2>
                        <p class="mt-4 text-[15.5px] leading-relaxed muted">{{ $project->description }}</p>
                    </div>
                @endif

                {{-- Team + Department --}}
                @if ($project->lead_name || $team->isNotEmpty() || $project->department)
                    <div class="reveal rounded-[1.75rem] border border-ink-100 p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600"><x-ui-icon name="users" class="h-5 w-5" /></span>
                            <h2 class="font-display text-xl font-bold text-ink-950">{{ __('site.projects.team') }}</h2>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @if ($project->lead_name)
                                <div class="rounded-2xl bg-ink-50 p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-400">{{ __('site.projects.team_lead') }}</p>
                                    <p class="mt-1 font-semibold text-ink-950">{{ $project->lead_name }}</p>
                                </div>
                            @endif
                            @if ($project->department)
                                <div class="rounded-2xl bg-ink-50 p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-400">{{ __('site.projects.department') }}</p>
                                    <p class="mt-1 font-semibold text-ink-950">{{ $project->department_name }}</p>
                                </div>
                            @endif
                        </div>
                        @if ($team->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($team as $member)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 py-1 pl-1 pr-3 text-[13px] font-medium text-ink-700">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-600 text-[10px] font-bold text-white">{{ mb_substr($member, 0, 1) }}</span>
                                        {{ $member }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Technology --}}
                @if (! empty($project->technologies))
                    <div class="reveal rounded-[1.75rem] border border-ink-100 p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600"><x-ui-icon name="cpu" class="h-5 w-5" /></span>
                            <h2 class="font-display text-xl font-bold text-ink-950">{{ __('site.projects.technology') }}</h2>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach ($project->technologies as $tech)
                                <span class="rounded-xl border border-brand-100 bg-brand-50 px-3.5 py-2 text-[13.5px] font-semibold text-brand-700">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Photos / Video --}}
                @if ($photos->count() > 1 || $project->video_url)
                    <div class="reveal rounded-[1.75rem] border border-ink-100 p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600"><x-ui-icon name="play" class="h-5 w-5" /></span>
                            <h2 class="font-display text-xl font-bold text-ink-950">{{ __('site.projects.media') }}</h2>
                        </div>

                        @if ($embed)
                            <div class="mt-5 aspect-video overflow-hidden rounded-2xl bg-ink-950">
                                <iframe src="{{ $embed }}" title="{{ __('site.projects.video_title', ['title' => $project->title]) }}" class="h-full w-full" loading="lazy"
                                        allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @elseif ($project->video_url)
                            <a href="{{ $project->video_url }}" target="_blank" rel="noopener noreferrer" class="btn-ghost mt-5">
                                <x-ui-icon name="play" class="h-4 w-4" /> {{ __('site.projects.watch_video') }}
                            </a>
                        @endif

                        @if ($photos->count() > 1)
                            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach ($photos as $photo)
                                    <a href="{{ Storage::url($photo) }}" target="_blank" rel="noopener" class="group block overflow-hidden rounded-2xl">
                                        <img src="{{ Storage::url($photo) }}" alt="{{ __('site.projects.photo_alt', ['title' => $project->title, 'number' => $loop->iteration]) }}" loading="lazy"
                                             class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Research → Patent/IP → Commercial potential --}}
                @foreach ([
                    [__('site.projects.research'), 'beaker', $project->research_summary],
                    [__('site.projects.patent'), 'key', $project->patent_details],
                    [__('site.projects.commercial'), 'rocket', $project->commercial_potential],
                    [__('site.projects.outcome'), 'target', $project->outcome],
                ] as [$title, $icon, $text])
                    @if (filled($text))
                        <div class="reveal rounded-[1.75rem] border border-ink-100 p-7">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600"><x-ui-icon :name="$icon" class="h-5 w-5" /></span>
                                <h2 class="font-display text-xl font-bold text-ink-950">{{ $title }}</h2>
                            </div>
                            <p class="mt-4 text-[15.5px] leading-relaxed muted">{{ $text }}</p>
                        </div>
                    @endif
                @endforeach
            </article>

            {{-- Fact panel --}}
            <aside class="lg:sticky lg:top-32 lg:self-start">
                <div class="reveal overflow-hidden rounded-[2rem] border border-ink-100 bg-white">
                    @if ($project->code)
                        <div class="bg-ink-950 px-6 py-5 text-white">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-white/60">{{ __('site.projects.project_id') }}</p>
                            <p class="mt-1 font-mono text-lg font-bold tracking-wide">{{ $project->code }}</p>
                        </div>
                    @endif

                    <dl class="space-y-4 p-6">
                        @foreach ([
                            [__('site.projects.fact_type'), $project->type_label, 'grid'],
                            [__('site.projects.department'), $project->department_name, 'building'],
                            [__('site.projects.fact_innovation_area'), $project->innovationArea?->name, 'lightbulb'],
                            [__('site.projects.fact_consultancy_area'), $project->category?->name, 'briefcase'],
                            [__('site.projects.fact_client'), $project->client, 'handshake'],
                            [__('site.projects.current_stage'), $project->stage_label, 'target'],
                            [__('site.projects.patent'), $project->patent_status !== 'none' ? $project->patent_status_label : null, 'key'],
                            [__('site.projects.fact_commercialization'), $project->commercialization_status !== 'none' ? $project->commercialization_status_label : null, 'rocket'],
                            [__('site.projects.fact_duration'), $project->duration, 'clock'],
                            [__('site.projects.fact_year'), $project->year, 'calendar'],
                            [__('site.projects.fact_status'), Str::title($project->status), 'check'],
                        ] as [$label, $value, $icon])
                            @if (filled($value))
                                <div class="flex gap-3.5">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                        <x-ui-icon :name="$icon" class="h-4 w-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <dt class="text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-400">{{ $label }}</dt>
                                        <dd class="mt-0.5 font-medium text-ink-950">{{ $value }}</dd>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </dl>

                    <div class="border-t border-ink-100 p-6">
                        <a href="{{ $commissionUrl }}" class="btn-primary w-full">
                            {{ $isInnovation ? __('site.projects.collaborate_cta') : __('site.projects.commission_cta') }}
                            <x-ui-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-ink-100 bg-ink-50 py-20">
            <div class="container-rich">
                <x-section-heading :eyebrow="__('site.projects.more_eyebrow')" :title="$isInnovation ? __('site.projects.more_innovation') : __('site.projects.more_related')" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $i => $item)
                        <x-cards.project-image-card :project="$item" :index="$i" class="min-h-[24rem]" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts.app>
