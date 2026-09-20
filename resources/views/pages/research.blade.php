<x-layouts.app title="Research"
               description="Research areas and ongoing research studies at UGV RICH, with a researcher search.">

    <x-page-hero
        eyebrow="Research"
        title='Evidence that stands up to <span class="text-accent">scrutiny</span>'
        lead="UGV RICH facilitates research across faculty and student projects, interdisciplinary and collaborative work, and national and international partnerships."
        :breadcrumbs="['Research' => null]">
        <a href="{{ route('contact') }}" class="btn-primary">
            Propose a collaboration <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
        <a href="#researchers" class="btn-ghost">Find a researcher</a>
    </x-page-hero>

    {{-- ---------------- Research areas ---------------- --}}
    <section id="areas" class="scroll-mt-28 bg-white py-16 sm:py-20">
        <div class="container-rich">
            <x-section-heading
                eyebrow="Research areas"
                :title="'<span class=\'text-accent\'>'.$researchAreas->count().'</span> areas our researchers work in'"
                lead="Drawn from the expertise our faculty list on their profiles. Pick one to see who works on it." />

            @if ($researchAreas->isEmpty())
                <p class="mt-8 rounded-2xl border-2 border-dashed border-ink-200 p-8 text-center text-[14.5px] muted">
                    Research areas appear here as faculty profiles list their expertise.
                </p>
            @else
                <div class="reveal mt-10 flex flex-wrap gap-2">
                    @foreach ($researchAreas as $area => $count)
                        <a href="{{ route('experts.index', ['q' => $area]) }}"
                           class="group inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-4 py-2.5 text-[13.5px] font-medium text-ink-700 transition duration-300 hover:-translate-y-0.5 hover:border-brand-400 hover:text-brand-700">
                            {{ $area }}
                            <span class="rounded-full bg-ink-100 px-1.5 py-0.5 text-[11px] font-bold text-ink-600 transition group-hover:bg-brand-600 group-hover:text-white">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ---------------- Ongoing research ---------------- --}}
    <section id="ongoing" class="scroll-mt-28 border-t border-ink-100 bg-ink-50 py-16 sm:py-20">
        <div class="container-rich">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="Ongoing research"
                    title="Ongoing research"
                    lead="Studies currently running, with their department and lead." />
                <span class="reveal hidden shrink-0 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[13px] font-medium text-ink-600 sm:inline-flex">
                    {{ $ongoing->count() }} {{ \Illuminate\Support\Str::plural('study', $ongoing->count()) }}
                </span>
            </div>

            @if ($ongoing->isEmpty())
                <div class="mt-9 rounded-[1.75rem] border-2 border-dashed border-ink-200 px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                        <x-ui-icon name="beaker" class="h-5 w-5" />
                    </span>
                    <p class="mt-4 font-display text-[16px] font-bold text-ink-950">No ongoing research listed yet</p>
                    <p class="mx-auto mt-2 max-w-md text-[14px] muted">Research projects added in the admin panel appear here.</p>
                </div>
            @else
                <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ongoing as $i => $project)
                        <x-cards.project-image-card :project="$project" :index="$i" class="min-h-[22rem]" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ---------------- Find a researcher ---------------- --}}
    <section id="researchers" class="scroll-mt-28 border-t border-ink-100 bg-white py-16 sm:py-20">
        <div class="container-rich">
            <x-section-heading
                eyebrow="Researchers"
                title='Find a <span class="text-accent">researcher</span>'
                lead="Search by name, department or research area." />

            {{-- Submits to the researcher directory, which does the filtering. --}}
            <form action="{{ route('experts.index') }}" method="GET" class="reveal mt-8 max-w-2xl">
                <div class="flex flex-col gap-2 sm:flex-row">
                    <label class="relative flex-1">
                        <span class="sr-only">Search researchers</span>
                        <x-ui-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-ink-400" />
                        <input type="search" name="q" value="{{ request('q') }}"
                               placeholder="Name, department or research area — e.g. Dr. Hasan, Civil, machine learning"
                               class="h-13 w-full rounded-2xl border border-ink-200 bg-white pl-12 pr-4 text-[14.5px] text-ink-900 placeholder:text-ink-400 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100">
                    </label>
                    <button type="submit" class="btn-primary h-13 !px-6">
                        Search <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </button>
                </div>
            </form>

            @if ($researchers->isNotEmpty())
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($researchers->take(6) as $i => $expert)
                        <x-cards.expert-profile-card :expert="$expert" :index="$i" />
                    @endforeach
                </div>

                @if ($researchers->count() > 6)
                    <div class="mt-10 text-center">
                        <a href="{{ route('experts.index') }}" class="btn-ghost">
                            All {{ $researchers->count() }} researchers <x-ui-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </section>

</x-layouts.app>
