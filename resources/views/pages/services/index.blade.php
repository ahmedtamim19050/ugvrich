<x-layouts.app title="Consultancy & Industry Services"
               description="Research, technical testing, engineering solutions, software development, professional training and consultancy from UGV RICH for industry, government, NGOs and business.">

    <x-page-hero
        eyebrow="Consultancy & industry services"
        title='From University Expertise to <span class="text-accent">Industry Solutions</span>'
        :breadcrumbs="['Consultancy' => null]">
        <a href="{{ route('consultancy.create') }}" class="btn-primary">
            Request Consultancy <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
        <a href="#services" class="btn-ghost">Browse the catalogue</a>
    </x-page-hero>

    {{-- The positioning statement. This is the page's revenue pitch, so it is
         set large, with the services marked and the clients called out. --}}
    <section class="relative isolate overflow-hidden border-b border-ink-100 bg-white py-16 sm:py-20">
        <div class="pointer-events-none absolute -left-24 top-0 -z-10 h-72 w-72 rounded-full bg-brand-100/50 blur-3xl" aria-hidden="true"></div>

        <div class="container-rich">
            <div class="reveal relative max-w-4xl border-l-4 border-brand-600 py-2 pl-7 sm:pl-10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-600">What UGV RICH offers</p>

                <p class="mt-4 font-display text-[21px] font-semibold leading-[1.5] text-ink-950 sm:text-[26px] sm:leading-[1.5]">
                    UGV RICH provides
                    <span class="mark">research</span>, <span class="mark">technical testing</span>,
                    <span class="mark">engineering solutions</span>, <span class="mark">software development</span>,
                    <span class="mark">professional training</span> and <span class="mark">consultancy services</span>
                    to <span class="text-brand-700">industries</span>, <span class="text-brand-700">government organizations</span>,
                    <span class="text-brand-700">NGOs</span> and <span class="text-brand-700">businesses</span>.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-x-8 gap-y-3">
                    @foreach ([['building', 'Industries'], ['shield', 'Government organizations'], ['heart', 'NGOs'], ['briefcase', 'Businesses']] as [$icon, $label])
                        <span class="inline-flex items-center gap-2 text-[13.5px] font-medium text-ink-600">
                            <x-ui-icon :name="$icon" class="h-4 w-4 text-brand-600" /> {{ $label }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ---------------- Services by consultancy area ---------------- --}}
    <section id="services" class="scroll-mt-28 bg-white py-20 sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="Service catalogue"
                    :title="'<span class=\'text-accent\'>'.$serviceCount.'</span> services across '.$categories->count().' consultancy areas'"
                    lead="Each area draws on its own faculty and professional associates. Assignments often combine several." />

                <a href="{{ route('consultancy.create') }}" class="btn-primary reveal shrink-0">
                    Request Consultancy <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                </a>
            </div>

            <div class="mt-16 space-y-20 sm:space-y-24">
                @foreach ($categories as $i => $category)
                    <div id="{{ $category->slug }}" class="scroll-mt-28">
                        <div class="flex flex-col gap-6 border-b border-ink-100 pb-7 lg:flex-row lg:items-end lg:justify-between">
                            <div class="reveal max-w-2xl">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                        <x-ui-icon :name="$category->icon ?? 'grid'" class="h-5 w-5" />
                                    </span>
                                    <h2 class="font-display text-[22px] font-bold leading-tight text-ink-950 sm:text-[26px]">{{ $category->name }}</h2>
                                    <span class="rounded-full bg-ink-100 px-2.5 py-1 text-[12px] font-bold text-ink-600">
                                        {{ $category->services->count() }} {{ \Illuminate\Support\Str::plural('service', $category->services->count()) }}
                                    </span>
                                </div>

                                @if ($category->description)
                                    <p class="mt-4 text-[14.5px] leading-relaxed muted">{{ $category->description }}</p>
                                @endif
                            </div>

                            <a href="{{ route('services.show', $category) }}" class="btn-ghost reveal shrink-0">
                                Area detail <x-ui-icon name="arrow-right" class="h-4 w-4" />
                            </a>
                        </div>

                        <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($category->services as $j => $service)
                                <div class="group reveal flex flex-col rounded-2xl border border-ink-100 bg-white p-6 transition duration-500 hover:-translate-y-1 hover:border-brand-300 hover:shadow-[0_24px_50px_-32px_rgba(2,34,81,0.45)]"
                                     style="transition-delay: {{ min($j * 45, 300) }}ms">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-display text-[15.5px] font-semibold leading-snug text-ink-950">{{ $service->name }}</h3>
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                                            <x-ui-icon name="check" class="h-3.5 w-3.5" stroke="2.4" />
                                        </span>
                                    </div>

                                    @if ($service->description)
                                        <p class="mt-3 text-[13.5px] leading-relaxed muted">{{ $service->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</x-layouts.app>
