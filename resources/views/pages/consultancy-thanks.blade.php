<x-layouts.app title="Request received"
               description="Your consultancy request has been received by UGV RICH.">

    @php
        $firstName = \Illuminate\Support\Str::of($submission['name'] ?? '')
            ->replaceMatches('/^(Dr\.?|Prof\.?|Mr\.?|Ms\.?|Mrs\.?|Md\.?|Engr\.?)\s+/i', '')
            ->explode(' ')->first();
        $steps = $site->list('process_steps');
        $stepIcons = ['document', 'users', 'target', 'check', 'star'];
        $email = $site->get('contact_email');
    @endphp

    <section class="relative isolate overflow-hidden bg-ink-50 py-16 sm:py-24">
        <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-40 [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute left-1/2 top-24 -z-10 h-[34rem] w-[34rem] -translate-x-1/2 rounded-full border border-brand-100" aria-hidden="true"></div>
        <div class="pointer-events-none absolute left-1/2 top-44 -z-10 h-[22rem] w-[22rem] -translate-x-1/2 rounded-full border border-brand-100" aria-hidden="true"></div>

        <div class="container-rich">
            <div class="mx-auto max-w-3xl">
                {{-- Confirmation --}}
                <div class="text-center">
                    <span class="relative mx-auto flex h-20 w-20 items-center justify-center">
                        <span class="absolute inset-0 animate-ping rounded-full bg-brand-400 opacity-20" aria-hidden="true"></span>
                        <span class="absolute inset-0 rounded-full bg-brand-100" aria-hidden="true"></span>
                        <span class="relative flex h-14 w-14 items-center justify-center rounded-full bg-brand-600 text-white shadow-[0_14px_30px_-12px_var(--color-brand-600)]">
                            <x-ui-icon name="check" class="h-7 w-7" stroke="2.6" />
                        </span>
                    </span>

                    <span class="eyebrow mt-8">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>Request received
                    </span>

                    <h1 class="mt-5 font-display text-4xl font-bold leading-[1.1] tracking-tight text-ink-950 sm:text-5xl">
                        Thank you{{ $firstName ? ', '.$firstName : '' }}.
                    </h1>
                    <p class="mx-auto mt-5 max-w-xl text-[17px] leading-relaxed muted">
                        Your consultancy request has been received. Our coordination team will review it and be in touch shortly.
                    </p>
                </div>

                {{-- Summary --}}
                <div class="mt-12 overflow-hidden rounded-[2rem] border border-ink-100 bg-white shadow-[0_30px_70px_-50px_rgba(11,15,24,0.45)]">
                    <div class="grid divide-y divide-ink-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                        <div class="p-6">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">Reference</p>
                            <p class="mt-1.5 font-display text-lg font-bold tabular-nums text-brand-700">{{ $submission['reference'] }}</p>
                        </div>
                        <div class="min-w-0 p-6">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">We will reply to</p>
                            <p class="mt-1.5 truncate font-display text-[15.5px] font-semibold text-ink-950" title="{{ $submission['email'] }}">{{ $submission['email'] }}</p>
                        </div>
                        <div class="min-w-0 p-6">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">Area of interest</p>
                            <p class="mt-1.5 font-display text-[15.5px] font-semibold leading-snug text-ink-950">{{ $submission['area'] ?? 'To be matched by our team' }}</p>
                        </div>
                    </div>
                    <p class="flex items-center gap-2 border-t border-ink-100 bg-ink-50/70 px-6 py-3.5 text-[13px] muted">
                        <x-ui-icon name="lightbulb" class="h-4 w-4 shrink-0 text-brand-600" />
                        Keep your reference number handy if you contact us about this request.
                    </p>
                </div>

                {{-- Next steps --}}
                @if ($steps)
                    <div class="mt-6 rounded-[2rem] bg-brand-700 p-6 text-white sm:p-8">
                        <h2 class="font-display text-xl font-bold !text-white">What happens next</h2>
                        <ol class="mt-6 grid gap-4 sm:grid-cols-3">
                            @foreach ($steps as $i => $step)
                                <li class="rounded-2xl border border-white/10 bg-white/[0.07] p-5">
                                    <div class="flex items-center justify-between">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-brand-700">
                                            <x-ui-icon :name="$stepIcons[$i] ?? 'check'" class="h-5 w-5" />
                                        </span>
                                        <span class="font-display text-[12px] font-bold tabular-nums text-brand-200">0{{ $i + 1 }}</span>
                                    </div>
                                    <p class="mt-4 font-display text-[15.5px] font-semibold !text-white">{{ $step['title'] }}</p>
                                    @if (! empty($step['description']))
                                        <p class="mt-1.5 text-[13px] leading-relaxed text-white/70">{{ $step['description'] }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home') }}" class="btn-primary group">
                        Back to home <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </a>
                    <a href="{{ route('experts.index') }}" class="btn-ghost">Meet our experts</a>
                    <a href="{{ route('projects.index') }}" class="btn-ghost">See our projects</a>
                </div>

                @if ($email)
                    <p class="mt-8 text-center text-[14px] muted">
                        Need to add something? Email
                        <a href="mailto:{{ $email }}?subject={{ rawurlencode('Consultancy request '.$submission['reference']) }}" class="font-semibold text-brand-700 hover:underline">{{ $email }}</a>
                        quoting your reference.
                    </p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
