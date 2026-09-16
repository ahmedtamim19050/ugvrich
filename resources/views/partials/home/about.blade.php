@php
    $visionMedia = $site->get('vision_media')
        ? Storage::url($site->get('vision_media'))
        : asset('media/about/vision.jpg');
@endphp

<section class="bg-white py-24 sm:py-28">
    <div class="container-rich grid items-start gap-12 lg:grid-cols-[0.86fr_1.14fr] lg:gap-16">

        {{-- Left: vision over a photo, then the numbers.
             Sticky, so the shorter column stays with the prose beside it
             instead of leaving a hole at the bottom. --}}
        <div class="lg:sticky lg:top-28 lg:self-start">
            <div class="vision-card reveal group aspect-[4/5] p-8 sm:aspect-[5/6]">
                <img src="{{ $visionMedia }}" alt="" aria-hidden="true" loading="lazy" class="vision-media">
                <span class="vision-scrim"></span>

                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/12 text-white
                             ring-1 ring-inset ring-white/25 transition duration-500 group-hover:scale-110">
                    <x-ui-icon name="target" class="h-5 w-5" />
                </span>

                <p class="mt-6 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">Our vision</p>

                <p class="mt-3 font-display text-[21px] font-semibold leading-[1.4] text-white sm:text-[23px]">
                    {{ $site->get('vision') }}
                </p>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4">
                @foreach ($stats as $stat)
                    <div class="stat-tile reveal"
                         style="transition-delay: {{ min($loop->index * 70, 280) }}ms"
                         x-data="counter({{ (int) preg_replace('/\D/', '', $stat->value) }})"
                         x-intersect.once="start()">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <x-ui-icon :name="$stat->icon ?? 'chart'" class="h-5 w-5" />
                        </span>
                        <p class="mt-4 font-display text-[30px] font-bold leading-none text-ink-950">
                            <span x-text="value">{{ $stat->value }}</span><span class="text-brand-600">{{ $stat->suffix }}</span>
                        </p>
                        <p class="mt-2 text-[13px] leading-snug muted">{{ $stat->label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: the written profile --}}
        <div>
            <x-section-heading
                eyebrow="About UGV RICH"
                title='An institutional platform, not a <span class="text-accent">side project</span>' />

            <div class="prose-rich reveal mt-6 text-[16px] muted" style="transition-delay: 80ms">
                <p>{{ $site->get('about_intro') }}</p>
                <p>{{ $site->get('about_body') }}</p>
            </div>

            <div class="mission-card reveal mt-9" style="transition-delay: 160ms">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white">
                        <x-ui-icon name="compass" class="h-5 w-5" />
                    </span>
                    <h3 class="font-display text-lg font-bold">Our mission</h3>
                </div>

                <p class="mt-4 text-[14.5px] muted">{{ $site->get('mission_intro') }}</p>

                <ul class="mt-5 grid gap-x-6 gap-y-3 sm:grid-cols-2">
                    @foreach ($site->list('mission_points') as $point)
                        <li class="flex items-start gap-2.5 text-[14px] muted">
                            <x-ui-icon name="check" class="mt-[3px] h-4 w-4 shrink-0 text-brand-600" stroke="2.4" />
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('about') }}" class="btn-primary reveal mt-9" style="transition-delay: 220ms">
                Read the full profile <x-ui-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>
