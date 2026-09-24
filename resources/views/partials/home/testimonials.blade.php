@if ($testimonials->isNotEmpty())
    <section class="bg-white py-24 sm:py-28" x-data="{ active: 0, total: {{ $testimonials->count() }} }"
             @keydown.left="active = (active - 1 + total) % total" @keydown.right="active = (active + 1) % total">
        <div class="container-rich">
            <x-section-heading
                align="center"
                :eyebrow="__('site.home.testimonials_eyebrow')"
                :title="__('site.home.testimonials_title')" />

            <div class="reveal relative mx-auto mt-14 max-w-3xl">
                <div class="relative isolate overflow-hidden rounded-[2rem] border border-ink-100 bg-white px-6 py-10 text-center sm:px-20 sm:py-14">
                    {{-- Oversized watermark quote --}}
                    <x-ui-icon name="quote" class="pointer-events-none absolute -left-4 -top-4 -z-10 h-36 w-36 text-brand-50" />
                    <x-ui-icon name="quote" class="pointer-events-none absolute -bottom-6 -right-4 -z-10 h-36 w-36 rotate-180 text-ink-50" />

                    {{-- Slides share one grid cell, so the card holds the tallest height and never jumps --}}
                    <div class="grid">
                        @foreach ($testimonials as $i => $testimonial)
                            <figure class="[grid-area:1/1] transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                    :class="active === {{ $i }} ? 'opacity-100 translate-y-0' : 'pointer-events-none opacity-0 translate-y-3'"
                                    :aria-hidden="active !== {{ $i }}"
                                    @if ($i > 0) x-cloak @endif>

                                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-[0_12px_26px_-12px_var(--color-brand-600)]">
                                    <x-ui-icon name="quote" class="h-6 w-6" />
                                </span>

                                <blockquote class="mt-8 font-display text-[19px] font-medium leading-[1.65] text-ink-950 sm:text-[23px]">
                                    &ldquo;{{ $testimonial->quote }}&rdquo;
                                </blockquote>

                                <div class="mx-auto mt-8 flex w-fit items-center gap-1 rounded-full border border-ink-100 bg-ink-50 px-3 py-1.5">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <x-ui-icon name="star-solid" @class([
                                            'h-4 w-4',
                                            'text-brand-600' => $s <= $testimonial->rating,
                                            'text-ink-200' => $s > $testimonial->rating,
                                        ]) />
                                    @endfor
                                    <span class="ml-1.5 text-[12px] font-semibold text-ink-700">{{ number_format($testimonial->rating, 1) }}</span>
                                </div>

                                <figcaption class="mt-7 flex items-center justify-center gap-3.5">
                                    @if ($testimonial->photo)
                                        <img src="{{ Storage::url($testimonial->photo) }}" alt="{{ $testimonial->name }}"
                                             class="h-12 w-12 rounded-full object-cover ring-4 ring-brand-50">
                                    @else
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-600 text-[13px] font-bold text-white ring-4 ring-brand-50">
                                            {{ collect(explode(' ', preg_replace('/^(Md\.?|Mr\.?|Ms\.?|Mrs\.?|Dr\.?|Engr\.?)\s+/i', '', $testimonial->name)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}
                                        </span>
                                    @endif
                                    <div class="text-left">
                                        <p class="font-display text-[15.5px] font-semibold text-ink-950">{{ $testimonial->name }}</p>
                                        <p class="mt-0.5 text-[12.5px] muted">
                                            {{ $testimonial->designation }}@if ($testimonial->organization)<span class="text-ink-300"> · </span>{{ $testimonial->organization }}@endif
                                        </p>
                                    </div>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>

                {{-- Controls: arrows either side of the card, dots below --}}
                @if ($testimonials->count() > 1)
                    <button type="button" @click="active = (active - 1 + total) % total" aria-label="Previous testimonial"
                            class="group absolute top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700 transition duration-300 hover:border-brand-600 hover:bg-brand-600 hover:text-white sm:-left-6 sm:flex">
                        <x-ui-icon name="arrow-left" class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5" />
                    </button>
                    <button type="button" @click="active = (active + 1) % total" aria-label="Next testimonial"
                            class="group absolute top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700 transition duration-300 hover:border-brand-600 hover:bg-brand-600 hover:text-white sm:-right-6 sm:flex">
                        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </button>
                @endif
            </div>

            @if ($testimonials->count() > 1)
                <div class="mt-8 flex items-center justify-center gap-2">
                    <button type="button" @click="active = (active - 1 + total) % total" aria-label="Previous testimonial" class="mr-3 flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white sm:hidden">
                        <x-ui-icon name="arrow-left" class="h-4 w-4" />
                    </button>
                    @foreach ($testimonials as $i => $t)
                        <button type="button" @click="active = {{ $i }}"
                                :class="active === {{ $i }} ? 'w-8 bg-brand-600' : 'w-2 bg-ink-200 hover:bg-ink-400'"
                                :aria-current="active === {{ $i }}"
                                class="h-2 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                aria-label="Go to testimonial {{ $i + 1 }}"></button>
                    @endforeach
                    <button type="button" @click="active = (active + 1) % total" aria-label="Next testimonial" class="ml-3 flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white sm:hidden">
                        <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </button>
                </div>
            @endif
        </div>
    </section>
@endif
