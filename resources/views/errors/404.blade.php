<x-layouts.app title="Page not found" description="The page you were looking for is not here.">

    <section class="relative isolate flex min-h-[70vh] items-center overflow-hidden border-b hairline bg-ink-50">
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-32 bg-gradient-to-t from-white to-transparent"></div>

        <div class="container-rich relative py-24 text-center">
            <span class="eyebrow">
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>Error 404
            </span>

            <h1 class="mx-auto mt-7 max-w-2xl font-display text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl">
                That page is not on the <span class="text-accent">map</span>
            </h1>

            <p class="mx-auto mt-5 max-w-xl text-[16.5px] leading-relaxed muted">
                The link may be out of date, or the page may have moved. Try the consultancy catalogue,
                the expert directory, or tell us what you were looking for.
            </p>

            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">
                    Back to home <x-ui-icon name="arrow-right" class="h-4 w-4" />
                </a>
                <a href="{{ route('services.index') }}" class="btn-ghost">Consultancy services</a>
                <a href="{{ route('contact') }}" class="btn-ghost">Contact us</a>
            </div>

            <div class="mx-auto mt-14 grid max-w-3xl gap-4 sm:grid-cols-3">
                @foreach ([
                    ['Experts', 'A searchable faculty directory', 'experts.index', 'users'],
                    ['Projects', 'Recent consultancy assignments', 'projects.index', 'briefcase'],
                    ['News & Events', 'Workshops and announcements', 'news.index', 'calendar'],
                ] as [$label, $blurb, $routeName, $icon])
                    <a href="{{ route($routeName) }}" class="card card-hover group text-left">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600
                                     transition group-hover:bg-brand-600 group-hover:text-white">
                            <x-ui-icon :name="$icon" class="h-5 w-5" />
                        </span>
                        <p class="mt-4 font-display text-[15px] font-semibold text-ink-950">{{ $label }}</p>
                        <p class="mt-1 text-[13px] muted">{{ $blurb }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
