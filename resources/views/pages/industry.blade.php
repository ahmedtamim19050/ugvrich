<x-layouts.app title="Industry Collaboration"
               description="The industry, government, NGO, university and development partners UGV RICH works with on research, contract assignments, testing, training and technology transfer.">

    <x-page-hero
        eyebrow="Industry collaboration"
        title='The organizations we <span class="text-accent">work with</span>'
        lead="UGV RICH works alongside industry, government organizations, NGOs, universities and development partners — on funded research, contract assignments, testing, training and technology transfer."
        :breadcrumbs="['Industry Collaboration' => null]">
        <a href="{{ route('consultancy.create') }}" class="btn-primary">
            Request Consultancy <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    </x-page-hero>

    {{-- ----------------------------------------------------------------
         Partners, as a wall of marks.

         One tile per organisation, all the same size, with the sector as a
         filter above rather than as headings between them: with eight
         partners across six sectors, grouped sections left more empty space
         than partners. A logo fills its tile where one is uploaded; where
         none is, the monogram stands in at the same size, so the wall reads
         evenly either way.
         ---------------------------------------------------------------- --}}
    @php
        $sectors = $partners
            ->groupBy(fn ($partner) => $partner->type ?: 'Other partners')
            ->map->count()
            ->sortDesc();
    @endphp

    <section class="bg-white py-16 sm:py-20">
        <div class="container-rich">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="Partners"
                    title='Who we already <span class="text-accent">work with</span>'
                    lead="Every organization here is a live collaboration: research run together, assignments delivered to an agreed brief, laboratory testing, training and student placements." />

                @if ($partners->isNotEmpty())
                    <div class="reveal shrink-0 text-left lg:text-right">
                        <span class="block font-display text-[40px] font-bold leading-none text-ink-950">{{ $partners->count() }}</span>
                        <span class="mt-1 block text-[13px] muted">
                            partner organizations · {{ $sectors->count() }} {{ \Illuminate\Support\Str::plural('sector', $sectors->count()) }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($partners->isEmpty())
                <p class="mt-10 rounded-2xl border border-dashed border-ink-200 bg-ink-50 p-10 text-center text-[14px] muted">
                    Partner organizations will be listed here shortly.
                </p>
            @else
                <div x-data="{ sector: 'all' }">
                    {{-- Filter by sector. Client-side: the whole list is eight
                         tiles, so a round trip to filter it would be absurd. --}}
                    <div class="reveal mt-10 flex flex-wrap items-center gap-2">
                        <button type="button" x-on:click="sector = 'all'"
                                class="rounded-full border px-4 py-1.5 text-[12.5px] font-semibold transition duration-200"
                                x-bind:class="sector === 'all'
                                    ? 'border-brand-600 bg-brand-600 text-white'
                                    : 'border-ink-200 bg-white text-ink-600 hover:border-brand-300 hover:text-brand-700'">
                            All <span class="ml-1 opacity-70">{{ $partners->count() }}</span>
                        </button>

                        @foreach ($sectors as $type => $count)
                            <button type="button" x-on:click="sector = @js($type)"
                                    class="rounded-full border px-4 py-1.5 text-[12.5px] font-semibold transition duration-200"
                                    x-bind:class="sector === @js($type)
                                        ? 'border-brand-600 bg-brand-600 text-white'
                                        : 'border-ink-200 bg-white text-ink-600 hover:border-brand-300 hover:text-brand-700'">
                                {{ $type }} <span class="ml-1 opacity-70">{{ $count }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($partners as $i => $partner)
                            @php
                                $type = $partner->type ?: 'Other partners';
                                $href = $partner->website ?: null;
                            @endphp

                            <{{ $href ? 'a' : 'div' }}
                                @if ($href) href="{{ $href }}" target="_blank" rel="noopener noreferrer" @endif
                                x-show="sector === 'all' || sector === @js($type)"
                                x-transition.opacity.duration.200ms
                                class="group reveal flex h-full flex-col items-center rounded-2xl border border-ink-100 bg-white p-6 text-center transition duration-500 hover:-translate-y-1 hover:border-brand-300 hover:shadow-[0_24px_50px_-32px_rgba(2,34,81,0.45)]"
                                style="transition-delay: {{ min($i * 45, 270) }}ms">

                                {{-- The mark, on its own field, so an uploaded logo
                                     and a monogram occupy exactly the same space. --}}
                                <span class="flex h-24 w-full items-center justify-center rounded-xl bg-ink-50 transition duration-300 group-hover:bg-brand-50">
                                    @if ($partner->logo)
                                        <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}"
                                             class="max-h-14 max-w-[80%] object-contain">
                                    @else
                                        <x-partner-mark :partner="$partner" size="lg" class="h-14 w-14 rounded-2xl text-[18px]" />
                                    @endif
                                </span>

                                <h3 class="mt-4 font-display text-[14.5px] font-bold leading-snug text-ink-950">{{ $partner->name }}</h3>
                                <p class="mt-1.5 text-[12px] uppercase tracking-[0.1em] text-ink-400">{{ $type }}</p>

                                @if ($href)
                                    <span class="mt-3 inline-flex items-center gap-1 text-[12.5px] font-semibold text-brand-600">
                                        Visit website
                                        <x-ui-icon name="arrow-up-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                                    </span>
                                @endif
                            </{{ $href ? 'a' : 'div' }}>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ---------------- Become a partner ---------------- --}}
    <section class="border-t border-ink-100 bg-ink-50 py-16 sm:py-20">
        <div class="container-rich">
            <div class="reveal flex flex-col gap-8 rounded-3xl border border-ink-100 bg-white p-8 sm:p-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-current"></span> Work with us</span>
                    <h2 class="mt-4 font-display text-[22px] font-bold leading-tight text-ink-950 sm:text-[26px]">
                        Bring us a problem worth solving
                    </h2>
                    <p class="mt-3 text-[14px] leading-relaxed muted">
                        Tell us what you are trying to solve and we will suggest the route — joint research, a contract
                        assignment, laboratory testing, training or a student placement.
                    </p>
                </div>

                <a href="{{ route('consultancy.create') }}" class="btn-primary shrink-0">
                    Request Consultancy <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
