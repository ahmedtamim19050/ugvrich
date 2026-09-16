@php
    $commissionUrl = route('contact').($project->category ? '?area='.$project->category->slug : '');
@endphp

<x-layouts.app :title="$project->title" :description="$project->summary">

    <x-page-hero
        :eyebrow="$project->category?->name ?? 'Project'"
        :title="$project->title"
        :lead="$project->summary"
        :image="$project->image"
        :breadcrumbs="['Projects' => route('projects.index'), Str::limit($project->title, 40) => null]" />

    <section class="py-16 bg-white sm:py-20">
        <div class="container-rich grid gap-12 lg:grid-cols-[1.4fr_0.6fr] lg:gap-16">

            <article>
                <x-media-frame :src="$project->image" :alt="$project->title" :seed="$project->slug"
                               icon="briefcase" ratio="aspect-[16/8]" class="reveal rounded-3xl" />

                <div class="prose-rich reveal mt-10 text-[16px] muted">
                    <h2 class="text-2xl font-display font-bold text-ink-950">About this project</h2>
                    <p class="mt-4">{{ $project->description }}</p>
                </div>

                @if ($project->outcome)
                    <div class="reveal mt-10 rounded-3xl border-l-4 border-brand-600 bg-brand-50 p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <x-ui-icon name="target" class="h-5 w-5" />
                            </span>
                            <h3 class="font-display text-lg font-bold">Project outcome</h3>
                        </div>
                        <p class="mt-4 text-[15.5px] leading-relaxed muted">{{ $project->outcome }}</p>
                    </div>
                @endif
            </article>

            {{-- Fact panel --}}
            <aside class="lg:sticky lg:top-32 lg:self-start">
                <div class="card reveal">
                    <h3 class="font-display text-lg font-bold">Project details</h3>

                    <dl class="mt-6 space-y-5">
                        @foreach ([
                            ['Client / Partner', $project->client, 'building'],
                            ['Duration', $project->duration, 'clock'],
                            ['Area of consultancy', $project->category?->name, 'grid'],
                            ['Year', $project->year, 'calendar'],
                            ['Status', Str::title($project->status), 'check'],
                        ] as [$label, $value, $icon])
                            @if ($value)
                                <div class="flex gap-3.5">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                        <x-ui-icon :name="$icon" class="h-4 w-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <dt class="text-[11.5px] font-semibold uppercase tracking-[0.12em] muted">{{ $label }}</dt>
                                        <dd class="mt-1 font-medium text-ink-950">{{ $value }}</dd>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </dl>

                    <a href="{{ $commissionUrl }}" class="btn-primary mt-8 w-full">
                        Commission similar work <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t hairline py-20 bg-ink-50">
            <div class="container-rich">
                <x-section-heading eyebrow="More work" title="Related projects" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $i => $item)
                        <x-cards.project-card :project="$item" :index="$i" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.home.cta')
</x-layouts.app>
