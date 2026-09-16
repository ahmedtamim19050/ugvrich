{{-- The one saturated block on the page: the closing call to action. --}}
@php
    $team = isset($experts) ? $experts->filter(fn ($e) => $e->photo)->take(5) : collect();
    $steps = [
        ['icon' => 'document', 'title' => 'Share your question', 'text' => 'Submit a short consultancy request.'],
        ['icon' => 'users', 'title' => 'We match the expertise', 'text' => 'Faculty and professional experts are assigned.'],
        ['icon' => 'target', 'title' => 'Receive a proposal', 'text' => 'Approach, team and timeline, ready to review.'],
    ];
@endphp

<section class="bg-white pb-24 pt-4 sm:pb-28">
    <div class="container-rich">
        <div class="reveal relative isolate overflow-hidden rounded-[2.5rem] bg-brand-600 px-7 py-14 sm:px-12 sm:py-16 lg:px-16 lg:py-20">
            {{-- Background texture --}}
            <div class="pointer-events-none absolute inset-0 -z-10 text-ink-950 grid-overlay opacity-60" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-40 -top-40 -z-10 h-[32rem] w-[32rem] rounded-full bg-brand-500/60" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-48 -left-32 -z-10 h-[28rem] w-[28rem] rounded-full border border-white/15" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-16 -z-10 h-[18rem] w-[18rem] rounded-full border border-white/10" aria-hidden="true"></div>

            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                {{-- Copy --}}
                <div class="text-center lg:text-left">
                    <span class="eyebrow-invert">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-60"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                        </span>
                        Work with us
                    </span>

                    <h2 class="mt-6 font-display text-3xl font-bold leading-[1.1] !text-white sm:text-[44px]">
                        Bring us the question your organisation
                        <span class="relative inline-block whitespace-nowrap">
                            cannot answer
                            <svg class="absolute -bottom-2 left-0 h-3 w-full text-white/40" viewBox="0 0 200 12" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M2 9 C 50 2, 150 2, 198 8" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                            </svg>
                        </span>
                        on its own
                    </h2>

                    <p class="mx-auto mt-6 max-w-xl text-[16.5px] leading-relaxed text-white/80 lg:mx-0">
                        Submit a consultancy request and we will match it to the right faculty and professional expertise,
                        then come back to you with a proposed approach, team and timeline.
                    </p>

                    <div class="mt-9 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                        <a href="{{ route('contact') }}" class="btn-invert group">
                            Submit a consultancy request
                            <x-ui-icon name="arrow-up-right" class="h-4 w-4 transition-transform duration-300 group-hover:rotate-45" />
                        </a>
                        <a href="{{ route('experts.index') }}"
                           class="btn group inline-flex border border-white/40 text-white transition hover:-translate-y-0.5 hover:border-white hover:bg-white/10">
                            <x-ui-icon name="search" class="h-4 w-4" />
                            Find an expert
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap items-center justify-center gap-2.5 lg:justify-start">
                        @foreach (['Confidential by default', 'Response within 3 working days', 'No obligation to proceed'] as $point)
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-[13px] text-white/90">
                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-white text-brand-600">
                                    <x-ui-icon name="check" class="h-2.5 w-2.5" stroke="3" />
                                </span>
                                {{ $point }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Request flow card --}}
                <div class="relative mx-auto w-full max-w-md lg:mx-0 lg:justify-self-end">
                    <div class="absolute inset-0 translate-x-3 translate-y-3 rotate-2 rounded-[2rem] bg-white/15" aria-hidden="true"></div>

                    <div class="relative rounded-[2rem] bg-white p-6 text-ink-900 shadow-[0_40px_80px_-40px_rgba(11,15,24,0.6)] sm:p-7">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-700">How a request works</p>
                                <p class="mt-1 font-display text-lg font-bold text-ink-950">From enquiry to proposal</p>
                            </div>
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                <x-ui-icon name="handshake" class="h-5 w-5" />
                            </span>
                        </div>

                        <ol class="relative mt-6 space-y-2">
                            {{-- Connector line --}}
                            <span class="absolute bottom-8 left-[1.6rem] top-8 w-px bg-ink-100" aria-hidden="true"></span>

                            @foreach ($steps as $n => $step)
                                <li class="group relative flex items-start gap-4 rounded-2xl p-2.5 transition hover:bg-ink-50">
                                    <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-600 ring-1 ring-ink-100 transition group-hover:bg-brand-600 group-hover:text-white group-hover:ring-brand-600">
                                        <x-ui-icon :name="$step['icon']" class="h-5 w-5" />
                                    </span>
                                    <span class="min-w-0 pt-0.5">
                                        <span class="flex items-center gap-2">
                                            <span class="font-display text-[11px] font-bold tabular-nums text-ink-300">0{{ $n + 1 }}</span>
                                            <span class="font-display text-[15px] font-semibold text-ink-950">{{ $step['title'] }}</span>
                                        </span>
                                        <span class="mt-0.5 block text-[13px] leading-snug muted">{{ $step['text'] }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ol>

                        @if ($team->isNotEmpty())
                            <div class="mt-5 flex items-center justify-between gap-4 rounded-2xl bg-ink-50 p-3.5">
                                <div class="flex -space-x-2.5">
                                    @foreach ($team as $member)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($member->photo) }}" alt="{{ $member->name }}" loading="lazy"
                                             class="h-9 w-9 rounded-full object-cover object-top ring-[3px] ring-ink-50">
                                    @endforeach
                                </div>
                                <a href="{{ route('experts.index') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-brand-700 transition hover:text-brand-800">
                                    Meet the experts <x-ui-icon name="arrow-right" class="h-3.5 w-3.5" />
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
