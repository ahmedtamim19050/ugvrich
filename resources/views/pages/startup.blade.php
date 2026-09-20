<x-layouts.app title="Startup & Incubation"
               description="From idea to enterprise: UGV RICH takes student and faculty ideas through evaluation, mentorship, prototyping, a business model, funding support and on to market.">

    <x-page-hero
        eyebrow="Startup & incubation"
        title='From Idea to <span class="text-accent">Enterprise</span>'
        lead="Students and faculty submit an idea. The Innovation Wing evaluates it, connects mentors and supports it from prototype to market."
        :breadcrumbs="['Startup & Incubation' => null]">
        <a href="{{ route('ideas.create') }}" class="btn-primary">
            Submit Your Innovation <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
        <a href="#journey" class="btn-ghost">See the journey</a>
    </x-page-hero>

    @php
        $stages = config('rich.startup_stages');

        // One icon and one line per stage, in the same order as the config.
        $detail = [
            'idea' => ['lightbulb', 'A student, faculty member or team submits the idea through the form.'],
            'evaluation' => ['check', 'The Innovation Wing reviews it for originality, feasibility and impact.'],
            'mentorship' => ['users', 'A faculty mentor and, where useful, an industry adviser are assigned.'],
            'prototype' => ['cog', 'The team builds a working model with lab access and technical support.'],
            'business_model' => ['chart', 'Costing, customers and route to market are worked out.'],
            'funding' => ['star', 'Seed support, grant applications and investor introductions.'],
            'startup' => ['rocket', 'The venture is formed, with IP and registration support.'],
            'market' => ['briefcase', 'The product reaches its users, and the hub stays in touch.'],
        ];
    @endphp

    {{-- ---------------- The journey ---------------- --}}
    <section id="journey" class="scroll-mt-28 bg-white py-16 sm:py-20">
        <div class="container-rich">
            <x-section-heading
                eyebrow="The journey"
                :title="'<span class=\'text-accent\'>'.count($stages).'</span> stages from idea to market'"
                lead="Every idea follows the same route. How long each stage takes depends on the idea, not on a timetable." />

            <ol class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($stages as $key => $label)
                    @php [$icon, $text] = $detail[$key] ?? ['check', '']; @endphp
                    <li class="reveal" style="transition-delay: {{ min($loop->index * 60, 420) }}ms">
                        <div class="group relative flex h-full flex-col rounded-2xl border border-ink-100 bg-white p-6 transition duration-500 hover:-translate-y-1 hover:border-brand-300 hover:shadow-[0_24px_50px_-32px_rgba(2,34,81,0.45)]">
                            {{-- The line that joins one stage to the next --}}
                            @unless ($loop->last)
                                <span class="pointer-events-none absolute -right-4 top-12 hidden h-px w-4 bg-ink-200 lg:block" aria-hidden="true"></span>
                            @endunless

                            <div class="flex items-center justify-between">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition duration-300 group-hover:bg-brand-600 group-hover:text-white">
                                    <x-ui-icon :name="$icon" class="h-5 w-5" />
                                </span>
                                <span class="font-display text-[13px] font-bold tabular-nums text-ink-300 transition-colors group-hover:text-brand-600">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <h3 class="mt-5 font-display text-[16px] font-bold leading-snug text-ink-950">{{ $label }}</h3>
                            @if ($text)
                                <p class="mt-2 text-[13.5px] leading-relaxed muted">{{ $text }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ---------------- What the hub provides ---------------- --}}
    <section class="border-t border-ink-100 bg-ink-50 py-16 sm:py-20">
        <div class="container-rich grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div>
                <x-section-heading
                    eyebrow="Support"
                    title='What you get from the <span class="text-accent">Innovation Wing</span>'
                    lead="Submitting an idea costs nothing and commits you to nothing. If it is taken forward, this is what comes with it." />

                <a href="{{ route('ideas.create') }}" class="btn-primary reveal mt-8">
                    Submit Your Innovation <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                </a>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ([
                    ['academic', 'A faculty mentor', 'Someone from the relevant department who knows the field.'],
                    ['beaker', 'Lab and workshop access', 'Department facilities for building and testing.'],
                    ['shield', 'IP and patent support', 'Help protecting novel work before it is shown publicly.'],
                    ['chart', 'Business model support', 'Costing, customers and the route to market.'],
                    ['star', 'Funding and grants', 'Seed support and help with grant applications.'],
                    ['handshake', 'Industry introductions', 'Partners, pilot customers and investors.'],
                ] as $i => [$icon, $title, $text])
                    <div class="reveal rounded-2xl border border-ink-100 bg-white p-5" style="transition-delay: {{ min($i * 50, 300) }}ms">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <x-ui-icon :name="$icon" class="h-4.5 w-4.5" />
                        </span>
                        <p class="mt-4 font-display text-[15px] font-semibold text-ink-950">{{ $title }}</p>
                        <p class="mt-1.5 text-[13px] leading-relaxed muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ---------------- Who can submit ---------------- --}}
    <section class="border-t border-ink-100 bg-white py-16 sm:py-20">
        <div class="container-rich">
            <div class="reveal relative isolate overflow-hidden rounded-[2.5rem] bg-navy-700 px-7 py-12 sm:px-12 sm:py-14">
                <div class="pointer-events-none absolute inset-0 -z-10 text-white grid-overlay opacity-20" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -right-24 -top-24 -z-10 h-72 w-72 rounded-full bg-brand-600/50" aria-hidden="true"></div>

                <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-14">
                    <div>
                        <h2 class="font-display text-[26px] font-bold leading-tight !text-white sm:text-[34px]">
                            Have an idea? Submit it.
                        </h2>
                        <p class="mt-4 max-w-xl text-[15.5px] leading-relaxed text-white/75">
                            Students, faculty, staff and alumni can all submit. Describe the problem and your solution — it does not need to be finished, and it does not need to be perfect.
                        </p>
                        <a href="{{ route('ideas.create') }}" class="btn-invert mt-8">
                            Submit Your Innovation <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach (config('rich.idea_roles') as $role)
                            <span class="rounded-full border border-white/15 bg-white/[0.07] px-4 py-2 text-[13.5px] font-medium text-white/85">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
