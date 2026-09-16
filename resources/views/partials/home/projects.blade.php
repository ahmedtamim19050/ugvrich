@if ($projects->isNotEmpty())
    <section class="py-24 bg-ink-50 sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="Projects & achievements"
                    title='Work that changed a <span class="text-accent">decision</span>'
                    lead="A selection of recent consultancy and research assignments delivered for government, industry, NGOs and academic partners." />

                <a href="{{ route('projects.index') }}" class="btn-ghost reveal shrink-0">
                    All projects <x-ui-icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3 {{ $projects->count() >= 3 ? 'lg:grid-rows-2' : '' }}">
                @foreach ($projects as $i => $project)
                    <x-cards.project-image-card :project="$project" :index="$i"
                        :featured="$loop->first"
                        :class="$loop->first ? 'md:col-span-2 lg:row-span-2' : ''" />
                @endforeach
            </div>
        </div>
    </section>
@endif
