<x-layouts.app title="News & Events"
               description="Workshops, agreements, research presentations and calls for participation from across UGV RICH.">

    <x-page-hero
        eyebrow="News & events"
        title='Latest from the <span class="text-accent">hub</span>'
        lead="Workshops, consultancy agreements, research presentations and calls for participation from across UGV RICH."
        :breadcrumbs="['News & Events' => null]" />

    {{-- Upcoming strip --}}
    @if ($upcoming->isNotEmpty())
        <section class="border-b hairline py-14 bg-ink-50">
            <div class="container-rich">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <x-ui-icon name="calendar" class="h-4.5 w-4.5" />
                    </span>
                    <h2 class="font-display text-lg font-bold">Upcoming events</h2>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($upcoming as $i => $event)
                        <a href="{{ route('news.show', $event) }}"
                           class="group reveal flex items-start gap-4 rounded-2xl border hairline p-5 transition hover:-translate-y-1 bg-white hover:border-brand-300"
                           style="transition-delay: {{ $i * 70 }}ms">
                            <span class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl
                                         bg-brand-600 text-white">
                                <span class="font-display text-lg font-bold leading-none">{{ $event->event_at->format('j') }}</span>
                                <span class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide">{{ $event->event_at->format('M') }}</span>
                            </span>
                            <div class="min-w-0">
                                <p class="font-display text-[15px] font-semibold leading-snug transition group-hover:text-brand-700">
                                    {{ Str::limit($event->title, 60) }}
                                </p>
                                @if ($event->location)
                                    <p class="mt-1.5 flex items-center gap-1.5 text-[12.5px] muted">
                                        <x-ui-icon name="map-pin" class="h-3.5 w-3.5" />{{ $event->location }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="py-16 bg-white sm:py-20">
        <div class="container-rich">
            {{-- Type filter --}}
            <div class="reveal flex flex-wrap items-center gap-2.5">
                @foreach ([['', 'All'], ['news', 'News'], ['event', 'Events']] as [$value, $label])
                    <a href="{{ $value ? route('news.index', ['type' => $value]) : route('news.index') }}"
                       @class([
                           'rounded-full border px-5 py-2 text-[13.5px] font-medium transition',
                           'border-brand-600 bg-brand-600 text-white' => $type === $value,
                           'hairline muted hover:border-brand-300 hover:text-brand-700' => $type !== $value,
                       ])>
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if ($posts->isEmpty())
                <div class="mt-10 rounded-3xl border border-dashed hairline p-14 text-center">
                    <x-ui-icon name="document" class="mx-auto h-9 w-9 muted" stroke="1.3" />
                    <p class="mt-4 font-display text-lg font-semibold">Nothing published here yet</p>
                    <p class="mt-2 text-[14.5px] muted">Check back soon, or subscribe below for updates.</p>
                </div>
            @else
                <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $i => $post)
                        <x-cards.post-card :post="$post" :index="$i" />
                    @endforeach
                </div>

                <div class="mt-14 border-t border-ink-100 pt-8">{{ $posts->links('vendor.pagination.rich') }}</div>
            @endif
        </div>
    </section>

</x-layouts.app>
