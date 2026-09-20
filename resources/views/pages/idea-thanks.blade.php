<x-layouts.app title="Idea received"
               description="Your innovation idea has been received by the UGV RICH Innovation Wing.">

    @php
        $firstName = \Illuminate\Support\Str::of($submission['name'] ?? '')
            ->replaceMatches('/^(Dr\.?|Prof\.?|Mr\.?|Ms\.?|Mrs\.?|Md\.?|Engr\.?)\s+/i', '')
            ->explode(' ')->first();
    @endphp

    <section class="relative isolate overflow-hidden bg-ink-50 py-16 sm:py-24">
        <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-40 [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]" aria-hidden="true"></div>

        <div class="container-rich">
            <div class="mx-auto max-w-2xl text-center">
                <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-brand-600 text-white">
                    <x-ui-icon name="lightbulb" class="h-8 w-8" />
                </span>

                <h1 class="mt-8 font-display text-4xl font-bold leading-[1.1] tracking-tight text-ink-950 sm:text-5xl">
                    Thank you{{ $firstName ? ', '.$firstName : '' }}.
                </h1>

                <p class="mx-auto mt-5 max-w-xl text-[17px] leading-relaxed muted">
                    Your idea has been received by the Innovation Wing. We review every submission and will be in touch by email.
                </p>

                <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('startup') }}" class="btn-primary group">
                        Back to Startup &amp; Incubation <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </a>
                    <a href="{{ route('innovation.index') }}" class="btn-ghost">Innovation Wing</a>
                    <a href="{{ route('projects.index') }}" class="btn-ghost">See our projects</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
