@if ($experts->isNotEmpty())
    <section class="py-24 bg-white sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="Our experts"
                    title='The people behind the <span class="text-accent">work</span>'
                    lead="A searchable directory of UGV faculty members and professional associates available for consultancy, collaboration and supervision." />

                <a href="{{ route('experts.index') }}" class="btn-ghost reveal shrink-0">
                    Browse the directory <x-ui-icon name="search" class="h-4 w-4" />
                </a>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7">
                @foreach ($experts as $i => $expert)
                    <x-cards.expert-profile-card :expert="$expert" :index="$i" />
                @endforeach
            </div>
        </div>
    </section>
@endif
