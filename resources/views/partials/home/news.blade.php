@if ($posts->isNotEmpty())
    <section class="py-24 bg-ink-50 sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="News & events"
                    title='Latest from the <span class="text-accent">hub</span>'
                    lead="Workshops, agreements, research presentations and calls for participation from across UGV RICH." />

                <a href="{{ route('news.index') }}" class="btn-ghost reveal shrink-0">
                    All news & events <x-ui-icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $i => $post)
                    <x-cards.post-image-card :post="$post" :index="$i" />
                @endforeach
            </div>
        </div>
    </section>
@endif
