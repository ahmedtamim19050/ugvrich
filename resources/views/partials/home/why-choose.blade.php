{{-- The page's dark feature block. It breaks a long run of light sections and
     gives the accent somewhere to actually stand out. --}}
<section class="relative isolate overflow-hidden bg-ink-950 py-24 sm:py-28">

    {{-- Ambience: a faint grid and two soft glows, no imagery to compete with. --}}
    <div class="pointer-events-none absolute inset-0 -z-10 text-white/70 grid-overlay opacity-[0.06]"></div>
    <div class="pointer-events-none absolute -left-40 top-0 -z-10 h-[420px] w-[420px] rounded-full bg-brand-600/25 blur-[130px]"></div>
    <div class="pointer-events-none absolute -right-32 bottom-0 -z-10 h-[380px] w-[380px] rounded-full bg-brand-500/15 blur-[130px]"></div>

    <div class="container-rich relative">
        <x-section-heading
            align="center"
            invert
            :eyebrow="__('site.home.why_eyebrow')"
            :title="__('site.home.why_title')"
            :lead="__('site.home.why_lead')" />

        {{-- Hairlines between rows rather than boxes around them. --}}
        <div class="mx-auto mt-14 max-w-5xl divide-y divide-white/10 border-y border-white/10">
            @foreach ($site->list('why_choose') as $i => $item)
                <div class="reason reveal" style="transition-delay: {{ min($i * 60, 300) }}ms">
                    <div class="reason-inner grid items-center gap-x-8 gap-y-4
                                lg:grid-cols-[3rem_minmax(0,17rem)_1fr_auto]">

                        <span class="reason-index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>

                        <h3 class="reason-title">{{ $item['title'] }}</h3>

                        <p class="text-[15px] leading-relaxed text-ink-300">{{ $item['description'] }}</p>

                        {{-- Shown at every size: .reason-icon is unlayered CSS, so a
                             `hidden` utility would not win against it anyway, and the
                             icon gives each stacked row an anchor on mobile. --}}
                        <span class="reason-icon">
                            <x-ui-icon :name="$item['icon'] ?? 'check'" class="h-5 w-5" />
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
