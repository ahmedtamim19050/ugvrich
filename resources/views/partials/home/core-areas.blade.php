<section id="core-areas" class="bg-white py-24 sm:py-28">
    <div class="container-rich">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <x-section-heading
                eyebrow="What we do"
                title='Four pillars, one <span class="text-accent">platform</span>'
                lead="Research, Innovation, Industry Services and Startup & Commercialization: four connected pillars that take knowledge from the lab to the market." />

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
