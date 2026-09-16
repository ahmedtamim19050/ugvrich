<section id="core-areas" class="bg-white py-24 sm:py-28">
    <div class="container-rich">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <x-section-heading
                eyebrow="What we do"
                title='Four pillars, one <span class="text-accent">platform</span>'
                lead="UGV RICH is organised around four connected functions. Together they take a question from research design, through innovation and professional delivery, into the sectors that need the answer." />

            <a href="{{ route('about') }}" class="btn-ghost reveal shrink-0">
                About UGV RICH <x-ui-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="mt-14 grid gap-7 md:grid-cols-2">
            @foreach ($coreAreas as $i => $area)
                <x-cards.core-area-card :area="$area" :index="$i" />
            @endforeach
        </div>
    </div>
</section>
