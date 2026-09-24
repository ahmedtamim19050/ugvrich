<section class="relative overflow-hidden bg-ink-50 py-24 sm:py-28">
    <div class="container-rich relative">
        <x-section-heading
            align="center"
            :eyebrow="__('site.home.services_eyebrow')"
            :title="__('site.home.services_title')"
            :lead="__('site.home.services_lead')" />

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $i => $category)
                <x-cards.service-card :category="$category" :index="$i" />
            @endforeach

            {{-- The tile that closes the grid. Dark over a photo rather than a
                 solid blue block, so the section keeps blue as an accent. --}}
            <a href="{{ route('contact') }}"
               class="service-cta group reveal"
               style="transition-delay: {{ min(count($categories) * 80, 320) }}ms">

                <img src="{{ asset('media/pillars/consultancy.jpg') }}" alt="" aria-hidden="true"
                     loading="lazy" class="service-cta-media">
                <span class="service-cta-scrim"></span>

                <span class="relative flex h-13 w-13 items-center justify-center rounded-2xl bg-white/12 p-3.5
                             text-white ring-1 ring-inset ring-white/25 transition duration-500 ease-out
                             group-hover:scale-110 group-hover:bg-white group-hover:text-ink-950">
                    <x-ui-icon name="handshake" class="h-6 w-6" />
                </span>

                <h3 class="relative mt-6 font-display text-[21px] font-bold leading-snug text-white">
                    Not sure which area fits your problem?
                </h3>

                <p class="relative mt-3 text-[14.5px] leading-relaxed text-ink-200">
                    Describe the decision you need to make. We will match it to the right faculty
                    and come back with a proposed approach, team and timeline.
                </p>

                <span class="relative mt-auto pt-7">
                    <span class="service-cta-btn">
                        Submit a consultancy request
                        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                    </span>
                </span>
            </a>
        </div>
    </div>
</section>
