{{--
    The innovation pipeline as a journey: interlocking chevrons, one soft colour
    per stage, following an idea from submission to market.
--}}
@php
    $stages = config('rich.pipeline_stages');

    $details = [
        'idea' => ['lightbulb', 'Students, faculty or industry submit an idea.'],
        'selected' => ['check', 'Promising ideas are shortlisted by the Innovation Wing.'],
        'research' => ['beaker', 'Teams study the problem and validate the approach.'],
        'prototype' => ['cog', 'A working model is designed and built.'],
        'testing' => ['target', 'The prototype is tested in real conditions.'],
        'patent' => ['shield', 'Novel work is protected through patents or IP.'],
        'incubation' => ['rocket', 'Mentorship, business model and funding support.'],
        'commercialization' => ['briefcase', 'The solution reaches users as a product or startup.'],
    ];

    // One soft, friendly colour per stage: pastel block, bright icon tile. Listed in full so Tailwind keeps every class.
    $tones = [
        'bg-sky-50 text-ink-950',
        'bg-indigo-50 text-ink-950',
        'bg-violet-50 text-ink-950',
        'bg-pink-50 text-ink-950',
        'bg-amber-50 text-ink-950',
        'bg-emerald-50 text-ink-950',
        'bg-teal-50 text-ink-950',
        'bg-blue-50 text-ink-950',
    ];
    $iconTones = [
        'bg-sky-500 text-white', 'bg-indigo-500 text-white', 'bg-violet-500 text-white', 'bg-pink-500 text-white',
        'bg-amber-500 text-white', 'bg-emerald-500 text-white', 'bg-teal-500 text-white', 'bg-blue-600 text-white',
    ];
@endphp

<div class="reveal relative isolate overflow-hidden rounded-[2rem] border border-ink-100 bg-white p-6 shadow-[0_24px_60px_-44px_rgba(11,15,24,0.4)] sm:p-10 {{ $class ?? '' }}">
    <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-25 [mask-image:linear-gradient(to_bottom,black,transparent_70%)]" aria-hidden="true"></div>

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-700">Innovation pipeline</p>
            <h3 class="mt-1.5 font-display text-2xl font-bold text-ink-950 sm:text-[28px]">From idea to market</h3>
        </div>
        <p class="flex items-center gap-2 text-[13px] font-medium text-ink-500">
            <span class="h-2 w-2 rounded-full bg-sky-500"></span> Idea
            <x-ui-icon name="arrow-right" class="h-3.5 w-3.5" />
            <span class="h-2 w-2 rounded-full bg-blue-600"></span> Market
        </p>
    </div>

    {{-- Journey --}}
    <ol class="mt-9 grid gap-2 sm:grid-cols-2 lg:grid-cols-4 lg:gap-y-3">
        @foreach ($stages as $key => $label)
            @php
                $i = $loop->index;
                [$icon, $text] = $details[$key] ?? ['check', ''];
            @endphp
            <li class="group relative">
                <div @class([
                    'relative flex h-full flex-col justify-between gap-5 rounded-2xl p-5 transition duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:-translate-y-1 group-hover:shadow-[0_22px_40px_-24px_rgba(11,15,24,0.35)] lg:rounded-none lg:py-6 lg:pl-10 lg:pr-9',
                    $tones[$i] ?? 'bg-sky-50 text-ink-950',
                    // Chevron shape on desktop: notch on the left (except the first of a row), point on the right.
                    'lg:[clip-path:polygon(0_0,calc(100%-22px)_0,100%_50%,calc(100%-22px)_100%,0_100%)] lg:pl-6' => $i % 4 === 0,
                    'lg:[clip-path:polygon(0_0,calc(100%-22px)_0,100%_50%,calc(100%-22px)_100%,0_100%,22px_50%)]' => $i % 4 !== 0,
                ])>
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl transition duration-500 group-hover:scale-110 group-hover:rotate-[-6deg] {{ $iconTones[$i] ?? 'bg-sky-500 text-white' }}">
                        <x-ui-icon :name="$icon" class="h-6 w-6" stroke="1.7" />
                    </span>
                    <div>
                        <p class="font-display text-[17px] font-bold leading-tight">{{ $label }}</p>
                        <p class="mt-1.5 text-[13px] leading-snug text-ink-600">{{ $text }}</p>
                    </div>
                </div>

                {{-- Mobile connector --}}
                @unless ($loop->last)
                    <span class="flex justify-center py-1 text-ink-300 sm:hidden" aria-hidden="true">
                        <x-ui-icon name="chevron-down" class="h-4 w-4" />
                    </span>
                @endunless
            </li>
        @endforeach
    </ol>
</div>
