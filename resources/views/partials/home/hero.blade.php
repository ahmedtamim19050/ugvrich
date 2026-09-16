@php
    // An uploaded video wins; otherwise the bundled clip ships as the default.
    $heroVideo = $site->get('hero_video')
        ? Storage::url($site->get('hero_video'))
        : asset('media/hero.mp4');

    $heroPoster = $site->get('hero_poster')
        ? Storage::url($site->get('hero_poster'))
        : asset('media/hero-poster.jpg');

    // Wrap the configured highlight word so the accent survives edits to the
    // heading from the admin panel.
    $heading = e((string) $site->get('hero_heading'));
    $highlight = trim((string) $site->get('hero_highlight', 'innovation'));

    $headingHtml = $highlight === ''
        ? $heading
        : preg_replace(
            '/'.preg_quote(e($highlight), '/').'/i',
            '<span class="text-brand-300">$0</span>',
            $heading,
            1,
        );
@endphp

{{--
    The header sits on top of this section, so the page opens straight onto the
    footage. This is the one dark block on an otherwise light site.
--}}
<section class="relative isolate flex min-h-[720px] items-center overflow-hidden bg-ink-950 lg:min-h-[800px]">

    {{-- Footage --}}
    <video class="absolute inset-0 -z-20 h-full w-full object-cover motion-reduce:hidden"
           autoplay muted loop playsinline
           poster="{{ $heroPoster }}"
           aria-hidden="true" tabindex="-1">
        <source src="{{ $heroVideo }}" type="video/mp4">
    </video>

    {{-- Still frame for anyone who has asked for reduced motion. --}}
    <img src="{{ $heroPoster }}" alt="" aria-hidden="true"
         class="absolute inset-0 -z-20 hidden h-full w-full object-cover motion-reduce:block">

    {{-- Scrims: heavier on the left, where the copy sits --}}
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950/92 via-ink-950/72 to-ink-950/45"></div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950/85 via-transparent to-ink-950/55"></div>

    <div class="container-rich relative w-full pb-24 pt-36 lg:pt-40">
        <div class="max-w-2xl">
            <span class="eyebrow-invert reveal">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                </span>
                {{ $site->get('hero_eyebrow') }}
            </span>

            <h1 data-split
                class="reveal mt-7 font-display text-[42px] font-bold leading-[1.04] tracking-tight text-white sm:text-[54px] lg:text-[62px]"
                style="transition-delay: 80ms">
                {!! $headingHtml !!}
            </h1>

            <p class="reveal mt-7 max-w-xl text-[17.5px] leading-[1.75] text-ink-200" style="transition-delay: 160ms">
                {{ $site->get('hero_subheading') }}
            </p>

            <div class="reveal mt-9 flex flex-wrap items-center gap-3" style="transition-delay: 240ms">
                <a href="{{ route('contact') }}" class="btn-primary group px-7 py-3.5 text-[15px]">
                    Request a consultancy
                    <x-ui-icon name="arrow-up-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </a>
                <a href="{{ route('services.index') }}"
                   class="btn px-7 py-3.5 text-[15px] border border-white/30 bg-white/10 text-white backdrop-blur-sm
                          hover:-translate-y-0.5 hover:bg-white hover:text-ink-950">
                    Explore our services
                </a>
            </div>

            {{-- Trust row --}}
            <div class="reveal mt-11 flex flex-wrap items-center gap-x-12 gap-y-6 border-t border-white/20 pt-7"
                 style="transition-delay: 320ms">
                @foreach ($stats->take(3) as $stat)
                    <div x-data="counter({{ (int) preg_replace('/\D/', '', $stat->value) }})" x-intersect.once="start()">
                        <p class="font-display text-[34px] font-bold leading-none text-white">
                            <span x-text="value">{{ $stat->value }}</span><span class="text-brand-300">{{ $stat->suffix }}</span>
                        </p>
                        <p class="mt-1.5 text-[13px] text-ink-300">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Floating card over the footage --}}
    <div class="pointer-events-none absolute right-[6%] top-1/2 z-10 hidden -translate-y-1/2 lg:block">
        <div class="animate-float rounded-3xl border border-white/20 bg-white/12 px-6 py-5 backdrop-blur-xl">
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-600">
                    <x-ui-icon name="star-solid" class="h-5 w-5" />
                </span>
                <div>
                    <p class="font-display text-2xl font-bold leading-none text-white">
                        {{ $stats->firstWhere('icon', 'star')?->value ?? '98' }}<span class="text-brand-300">%</span>
                    </p>
                    <p class="mt-1.5 text-[12.5px] text-ink-300">Client satisfaction</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll cue --}}
    <a href="#core-areas" aria-label="Scroll to content"
       class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-ink-300
              transition hover:text-white lg:flex">
        <span class="text-[10.5px] font-semibold uppercase tracking-[0.2em]">Scroll</span>
        <span class="flex h-9 w-6 items-start justify-center rounded-full border border-white/40 p-1.5">
            <span class="h-1.5 w-1 animate-bounce rounded-full bg-white"></span>
        </span>
    </a>
</section>
