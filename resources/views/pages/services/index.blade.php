<x-layouts.app title="Consultancy Services"
               description="Five practice areas drawing on the multidisciplinary expertise of UGV faculty and professional associates.">

    <x-page-hero
        eyebrow="Consultancy services"
        title='Expertise you can <span class="text-accent">commission</span>'
        lead="UGV RICH provides expert consultancy services drawing on the multidisciplinary expertise of UGV faculty and professionals. Browse the full catalogue below, or tell us the problem and we will match it."
        :breadcrumbs="['Services' => null]">
        <a href="{{ route('contact') }}" class="btn-primary">
            Request a consultancy <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    </x-page-hero>

    <section class="py-20 bg-white sm:py-24">
        <div class="container-rich space-y-20">
            @foreach ($categories as $i => $category)
                <div id="{{ $category->slug }}" class="scroll-mt-32">
                    <div class="flex flex-col gap-6 border-b hairline pb-8 lg:flex-row lg:items-end lg:justify-between">
                        <div class="reveal max-w-2xl">
                            <div class="flex items-center gap-4">
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                    <x-ui-icon :name="$category->icon ?? 'grid'" class="h-6 w-6" />
                                </span>
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-600">
                                        Area {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <h2 class="mt-1 font-display text-2xl font-bold sm:text-[30px]">{{ $category->name }}</h2>
                                </div>
                            </div>
                            <p class="mt-5 text-[15.5px] leading-relaxed muted">{{ $category->description }}</p>
                        </div>

                        <a href="{{ route('services.show', $category) }}" class="btn-ghost reveal shrink-0">
                            Area detail <x-ui-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($category->services as $j => $service)
                            <div class="group reveal rounded-2xl border hairline p-6 transition duration-300 hover:-translate-y-1 hover:border-brand-300"
                                 style="transition-delay: {{ min($j * 50, 300) }}ms">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-display text-[16px] font-semibold leading-snug">{{ $service->name }}</h3>
                                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                                        <x-ui-icon name="check" class="h-3.5 w-3.5" stroke="2.4" />
                                    </span>
                                </div>
                                <p class="mt-3 text-[13.5px] leading-relaxed muted">{{ $service->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @include('partials.home.cta')
</x-layouts.app>
