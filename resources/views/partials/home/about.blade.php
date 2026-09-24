@php
    $visionMedia = $site->get('vision_media')
        ? Storage::url($site->get('vision_media'))
        : asset('media/about/vision.jpg');

    $mission = $site->get('mission_statement')
        ?: collect($site->list('mission_points'))->map(fn ($p) => rtrim($p, '.'))->implode('; ');
@endphp

<section class="bg-white py-24 sm:py-28">
    <div class="container-rich grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">

        {{-- Left: the written profile --}}
        <div>
            <x-section-heading
                :eyebrow="__('site.home.about_eyebrow')"
                :title="__('site.home.about_title')" />

            <p class="reveal mt-6 text-[17.5px] leading-[1.75] text-ink-800" style="transition-delay: 80ms">
                {{ $site->get('about_intro') }}
            </p>
            <p class="reveal mt-4 text-[15.5px] leading-[1.75] muted" style="transition-delay: 120ms">
                {{ $site->get('about_body') }}
            </p>

            <ul class="reveal mt-8 flex flex-wrap gap-2" style="transition-delay: 160ms">
                @foreach ([['beaker', 'research'], ['lightbulb', 'innovation'], ['briefcase', 'industry'], ['rocket', 'entrepreneurship']] as [$icon, $label])
                    <li class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-ink-50 px-3.5 py-2 text-[13.5px] font-medium text-ink-700">
                        <x-ui-icon :name="$icon" class="h-4 w-4 text-brand-600" /> {{ __('site.home.chip_'.$label) }}
                    </li>
                @endforeach
            </ul>

            <a href="{{ route('about') }}" class="btn-primary group reveal mt-9" style="transition-delay: 220ms">
                {{ __('site.home.read_profile') }}
                <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
            </a>
        </div>

        {{-- Right: vision + mission --}}
        <div class="grid gap-5">
            {{-- Vision over a photo --}}
            <div class="reveal group relative isolate overflow-hidden rounded-[2rem] p-8 sm:p-10">
                <img src="{{ $visionMedia }}" alt="" aria-hidden="true" loading="lazy"
                     class="absolute inset-0 -z-20 h-full w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 -z-10 bg-gradient-to-br from-ink-950/90 via-ink-950/75 to-brand-900/70" aria-hidden="true"></div>

                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-700">
                        <x-ui-icon name="target" class="h-6 w-6" />
                    </span>
                    <p class="text-[12px] font-semibold uppercase tracking-[0.2em] text-brand-200">{{ __('site.home.our_vision') }}</p>
                </div>
                <p class="mt-6 font-display text-[21px] font-semibold leading-[1.45] !text-white sm:text-[24px]">
                    {{ $site->get('vision') }}
                </p>
            </div>

            {{-- Mission --}}
            <div class="reveal relative overflow-hidden rounded-[2rem] border border-brand-100 bg-brand-50 p-8 sm:p-10" style="transition-delay: 120ms">
                <x-ui-icon name="quote" class="pointer-events-none absolute -right-3 -top-3 h-28 w-28 text-brand-100" />

                <div class="relative flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white">
                        <x-ui-icon name="compass" class="h-6 w-6" />
                    </span>
                    <p class="text-[12px] font-semibold uppercase tracking-[0.2em] text-brand-700">{{ __('site.home.our_mission') }}</p>
                </div>
                <p class="relative mt-6 text-[17px] leading-[1.7] text-ink-800">{{ $mission }}</p>
            </div>
        </div>
    </div>
</section>
