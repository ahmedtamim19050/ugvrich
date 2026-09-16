<x-layouts.app title="Research & Innovation"
               description="Faculty and student research, interdisciplinary collaboration, grants, publications and innovation challenges at UGV RICH.">

    <x-page-hero
        eyebrow="Research & innovation"
        title='Evidence that stands up to <span class="text-accent">scrutiny</span>'
        lead="UGV RICH facilitates research across faculty and student projects, interdisciplinary and collaborative work, national and international partnerships, grants, conferences, publications, innovation challenges and community-based research."
        :breadcrumbs="['Research' => null]">
        <a href="{{ route('contact') }}" class="btn-primary">
            Propose a collaboration <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    </x-page-hero>

    @php
        $activities = $site->list('research_activities');
        $activityIcons = ['academic', 'lightbulb', 'grid', 'users', 'globe', 'star', 'calendar', 'document', 'sparkles', 'handshake'];
        $ease = 'ease-[cubic-bezier(0.22,1,0.36,1)]';
    @endphp

    {{-- ---------------- How research happens here ---------------- --}}
    @if ($activities)
        <section class="bg-white py-20 sm:py-28">
            <div class="container-rich grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
                <div class="lg:sticky lg:top-32 lg:self-start">
                    <x-section-heading
                        eyebrow="How research happens here"
                        :title="'<span class=\'text-accent\'>'.count($activities).'</span> routes into research at UGV RICH'"
                        lead="Whether you are a faculty member, a student, a partner institution or a funder, there is a defined route into research activity." />

                    <div class="reveal mt-8 rounded-[1.75rem] border border-ink-100 bg-ink-50/70 p-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-ink-400">Open to</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ([['academic', 'Faculty members'], ['users', 'Students'], ['building', 'Partner institutions'], ['handshake', 'Funders']] as [$icon, $label])
                                <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3.5 py-2 text-[13px] font-medium text-ink-700">
                                    <x-ui-icon :name="$icon" class="h-4 w-4 text-brand-600" /> {{ $label }}
                                </span>
                            @endforeach
                        </div>
                        <a href="{{ route('contact') }}" class="group mt-5 inline-flex items-center gap-2 text-[13.5px] font-semibold text-brand-700">
                            Propose a collaboration
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                        </a>
                    </div>
                </div>

                <ol class="grid content-start gap-3 sm:grid-cols-2">
                    @foreach ($activities as $i => $activity)
                        <li class="reveal" style="transition-delay: {{ min($i * 45, 400) }}ms">
                            <div class="group relative flex h-full items-center gap-4 overflow-hidden rounded-2xl border border-ink-100 bg-white p-4 pr-5 transition duration-500 {{ $ease }} hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-[0_18px_40px_-28px_rgba(27,77,255,0.5)]">
                                <span class="absolute inset-y-0 left-0 w-1 origin-top scale-y-0 bg-brand-600 transition-transform duration-500 group-hover:scale-y-100" aria-hidden="true"></span>
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition-colors duration-300 group-hover:bg-brand-600 group-hover:text-white">
                                    <x-ui-icon :name="$activityIcons[$i] ?? 'check'" class="h-5 w-5" />
                                </span>
                                <span class="min-w-0 flex-1 font-display text-[15.5px] font-semibold leading-snug text-ink-950">{{ $activity }}</span>
                                <span class="font-display text-[12px] font-bold tabular-nums text-ink-300 transition-colors group-hover:text-brand-600">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- ---------------- Core capability ---------------- --}}
    @if ($coreAreas->isNotEmpty())
        <section class="relative isolate overflow-hidden bg-ink-50 py-20 sm:py-28">
            <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-30 [mask-image:linear-gradient(to_bottom,black,transparent)]" aria-hidden="true"></div>

            <div class="container-rich">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <x-section-heading
                        eyebrow="Core capability"
                        title='What we can do for your <span class="text-accent">research question</span>' />
                    <a href="{{ route('about') }}" class="btn-ghost reveal shrink-0">
                        All core areas <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>

                <div class="mt-14 grid gap-7 md:grid-cols-2">
                    @foreach ($coreAreas as $i => $area)
                        <x-cards.core-area-card :area="$area" :index="$i" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ---------------- Research highlights + Funding ---------------- --}}
    <section class="bg-white py-20 sm:py-28">
        <div class="container-rich grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:gap-12">

            {{-- Publications --}}
            <div>
                <div class="flex items-end justify-between gap-4">
                    <x-section-heading eyebrow="Research highlights" title="Recent publications" />
                    @if ($publications->isNotEmpty())
                        <span class="reveal hidden shrink-0 rounded-full border border-ink-200 px-3.5 py-1.5 text-[13px] font-medium text-ink-600 sm:inline-flex">
                            {{ $publications->count() }} {{ \Illuminate\Support\Str::plural('paper', $publications->count()) }}
                        </span>
                    @endif
                </div>

                <div class="mt-9 space-y-4">
                    @forelse ($publications as $i => $item)
                        <article class="reveal" style="transition-delay: {{ min($i * 60, 300) }}ms">
                            <div class="group relative flex gap-5 overflow-hidden rounded-[1.5rem] border border-ink-100 bg-white p-5 transition duration-500 {{ $ease }} hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-[0_22px_48px_-30px_rgba(27,77,255,0.45)] sm:p-6">
                                {{-- Year tile --}}
                                <div class="flex w-16 shrink-0 flex-col items-center self-start overflow-hidden rounded-2xl border border-ink-100 text-center transition-colors group-hover:border-brand-200">
                                    <span class="w-full bg-ink-950 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-white transition-colors group-hover:bg-brand-600">Year</span>
                                    <span class="py-2 font-display text-[17px] font-bold tabular-nums text-ink-950">{{ $item->year ?: '—' }}</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    @if ($item->venue)
                                        <span class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 text-[11.5px] font-semibold text-brand-700">
                                            <x-ui-icon name="document" class="h-3.5 w-3.5 shrink-0" />
                                            <span class="truncate">{{ $item->venue }}</span>
                                        </span>
                                    @endif
                                    <h3 class="mt-3 font-display text-[17px] font-bold leading-snug text-ink-950 transition-colors group-hover:text-brand-700">{{ $item->title }}</h3>
                                    @if ($item->authors)
                                        <p class="mt-2 flex items-center gap-1.5 text-[13.5px] muted">
                                            <x-ui-icon name="users" class="h-3.5 w-3.5 shrink-0 text-ink-400" /> {{ $item->authors }}
                                        </p>
                                    @endif
                                    @if ($item->url)
                                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                                           class="mt-4 inline-flex items-center gap-1.5 text-[13px] font-semibold text-brand-600 after:absolute after:inset-0">
                                            View publication <x-ui-icon name="external" class="h-3.5 w-3.5" />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl border-2 border-dashed border-ink-200 p-8 text-center text-[14.5px] muted">Publications will be listed here.</p>
                    @endforelse
                </div>
            </div>

            {{-- Funding --}}
            <div class="lg:sticky lg:top-32 lg:self-start">
                <div class="reveal relative isolate overflow-hidden rounded-[2rem] bg-brand-700 p-6 text-white sm:p-8">
                    <div class="pointer-events-none absolute inset-0 -z-10 text-ink-950 grid-overlay opacity-60" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -right-20 -top-20 -z-10 h-56 w-56 rounded-full bg-brand-600" aria-hidden="true"></div>

                    <div class="flex items-center gap-3.5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-brand-700">
                            <x-ui-icon name="star" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-200">Funding</p>
                            <h2 class="font-display text-xl font-bold !text-white">Funded projects & grants</h2>
                        </div>
                    </div>

                    <div class="mt-7 space-y-3">
                        @forelse ($fundedProjects as $item)
                            <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-5 transition hover:border-white/25 hover:bg-white/[0.12]">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-display text-[15.5px] font-semibold leading-snug !text-white">{{ $item->title }}</h3>
                                    @if ($item->year)
                                        <span class="shrink-0 rounded-full bg-white px-2.5 py-0.5 font-display text-[12px] font-bold tabular-nums text-brand-700">{{ $item->year }}</span>
                                    @endif
                                </div>
                                <dl class="mt-3 space-y-1.5 text-[13px]">
                                    @if ($item->venue)
                                        <div class="flex items-center gap-2 text-white/80">
                                            <dt class="sr-only">Funder</dt>
                                            <x-ui-icon name="building" class="h-3.5 w-3.5 shrink-0 text-brand-200" />
                                            <dd>{{ $item->venue }}</dd>
                                        </div>
                                    @endif
                                    @if ($item->authors)
                                        <div class="flex items-center gap-2 text-white/70">
                                            <dt class="sr-only">Investigators</dt>
                                            <x-ui-icon name="users" class="h-3.5 w-3.5 shrink-0 text-brand-200" />
                                            <dd>{{ $item->authors }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/25 p-6 text-center text-[14px] text-white/70">Funded projects will be listed here.</p>
                        @endforelse
                    </div>

                    @if ($stats->isNotEmpty())
                        <div class="mt-6 grid grid-cols-2 gap-3 border-t border-white/15 pt-6">
                            @foreach ($stats->take(2) as $stat)
                                <div class="rounded-2xl bg-white p-4 text-ink-950" x-data="counter({{ (int) preg_replace('/\D/', '', $stat->value) }})" x-intersect.once="start()">
                                    <p class="font-display text-[28px] font-bold leading-none">
                                        <span x-text="value">{{ $stat->value }}</span><span class="text-brand-600">{{ $stat->suffix }}</span>
                                    </p>
                                    <p class="mt-1.5 text-[12.5px] leading-snug muted">{{ $stat->label }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ---------------- Applied research ---------------- --}}
    @if ($projects->isNotEmpty())
        <section class="border-t border-ink-100 bg-ink-50 py-20 sm:py-24">
            <div class="container-rich">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <x-section-heading eyebrow="Applied research" title='Research put to <span class="text-accent">work</span>' />
                    <a href="{{ route('projects.index') }}" class="btn-ghost reveal shrink-0">
                        All projects <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $i => $project)
                        <x-cards.project-image-card :project="$project" :index="$i" class="min-h-[24rem]" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.home.cta')
</x-layouts.app>
