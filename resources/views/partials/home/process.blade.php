@php $steps = $site->list('process_steps'); @endphp

@if ($steps)
    <section class="py-24 bg-white sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    eyebrow="How we work"
                    title='From enquiry to <span class="text-accent">evidence you can act on</span>'
                    lead="Every engagement follows the same three stages, whatever the discipline. You always know who is doing the work, on what timeline, and what you receive at the end." />

                <a href="{{ route('contact') }}" class="btn-primary reveal shrink-0">
                    Start an enquiry <x-ui-icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>

            <ol class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @foreach ($steps as $i => $step)
                    <li class="reveal relative" style="transition-delay: {{ $i * 110 }}ms">
                        <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-ink-100 bg-ink-50 p-8 transition duration-300 hover:-translate-y-1.5 hover:border-brand-100 hover:bg-white hover:shadow-[0_24px_50px_-24px_rgba(21,27,38,0.25)] sm:p-9">
                            {{-- Accent bar that grows on hover --}}
                            <span class="absolute inset-x-0 top-0 h-1 origin-left scale-x-0 bg-brand-600 transition-transform duration-500 group-hover:scale-x-100" aria-hidden="true"></span>

                            {{-- Oversized watermark number --}}
                            <span class="pointer-events-none absolute -right-2 -top-6 select-none font-display text-[8.5rem] font-bold leading-none text-ink-100 transition-colors duration-300 group-hover:text-brand-50" aria-hidden="true">
                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <div class="relative flex items-center justify-between">
                                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600 font-display text-lg font-bold text-white shadow-[0_10px_26px_-12px_var(--color-brand-600)] transition-transform duration-300 group-hover:rotate-[-6deg] group-hover:scale-105">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <p class="relative mt-8 text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-700">
                                Step {{ $i + 1 }} of {{ count($steps) }}
                            </p>
                            <h3 class="relative mt-2 font-display text-xl font-bold sm:text-2xl">{{ $step['title'] }}</h3>
                            <p class="relative mt-3 text-[15px] leading-relaxed muted">{{ $step['description'] }}</p>

                            {{-- Progress dots --}}
                            <div class="relative mt-auto flex gap-1.5 pt-8" aria-hidden="true">
                                @foreach ($steps as $j => $_)
                                    <span class="h-1.5 rounded-full transition-all duration-300 {{ $j <= $i ? 'w-6 bg-brand-600' : 'w-1.5 bg-ink-200' }}"></span>
                                @endforeach
                            </div>
                        </article>

                        {{-- Connector arrow between cards --}}
                        @unless ($loop->last)
                            <span class="absolute -right-4 top-1/2 z-10 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full border border-ink-100 bg-white text-brand-600 shadow-sm lg:flex" aria-hidden="true">
                                <x-ui-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        @endunless
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif
