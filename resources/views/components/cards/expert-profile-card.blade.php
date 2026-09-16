@props(['expert', 'index' => 0])

@php
    $url = route('experts.show', $expert);
    $tags = array_slice($expert->expertise ?? [], 0, 3);
    $moreTags = max(count($expert->expertise ?? []) - 3, 0);
    $department = \Illuminate\Support\Str::after($expert->department, 'Department of ');
    $area = $expert->category ? \Illuminate\Support\Str::before($expert->category->name, ' &') : null;
    $ease = 'ease-[cubic-bezier(0.22,1,0.36,1)]';
    $iconBtn = 'relative z-20 flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white';
@endphp

{{-- The scroll-reveal lives on the wrapper so its transform and delay never fight the card's hover. --}}
<div {{ $attributes->merge(['class' => 'reveal']) }} style="transition-delay: {{ min($index * 80, 400) }}ms">
    <article class="group relative flex h-full flex-col rounded-[1.75rem] border border-ink-100 bg-white p-2 shadow-[0_8px_24px_-18px_rgba(11,15,24,0.35)] transition-[transform,box-shadow,border-color] duration-500 {{ $ease }} hover:-translate-y-1.5 hover:border-brand-100 hover:shadow-[0_32px_60px_-30px_rgba(27,77,255,0.4)]">

        {{-- Portrait --}}
        <div class="relative isolate aspect-[5/4] overflow-hidden rounded-[1.4rem] bg-ink-100">
            @if ($expert->photo)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($expert->photo) }}" alt="{{ $expert->name }}" loading="lazy"
                     class="absolute inset-0 -z-10 h-full w-full object-cover object-[center_22%] transition-transform duration-[900ms] {{ $ease }} group-hover:scale-[1.06]">
            @else
                <div class="absolute inset-0 -z-10 flex items-center justify-center bg-brand-600" aria-hidden="true">
                    <span class="font-display text-7xl font-bold text-white/90">{{ $expert->initials }}</span>
                </div>
            @endif

            <div class="absolute inset-x-0 bottom-0 -z-10 h-1/2 bg-gradient-to-t from-ink-950/55 to-transparent" aria-hidden="true"></div>

            {{-- Designation --}}
            @if ($expert->designation)
                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.1em] text-ink-900 backdrop-blur-md">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                    {{ $expert->designation }}
                </span>
            @endif

            {{-- Consultancy area --}}
            @if ($area)
                <span class="absolute bottom-3 left-3 inline-flex max-w-[calc(100%-1.5rem)] items-center gap-1.5 truncate rounded-full border border-white/25 bg-ink-950/40 px-3 py-1.5 text-[11.5px] font-medium text-white backdrop-blur-md">
                    <x-ui-icon :name="$expert->category->icon ?? 'briefcase'" class="h-3.5 w-3.5 shrink-0" />
                    <span class="truncate">{{ $area }} consultancy</span>
                </span>
            @endif
        </div>

        {{-- Details --}}
        <div class="flex flex-1 flex-col px-4 pb-4 pt-5">
            <h3 class="font-display text-xl font-bold leading-snug">
                {{-- Stretched link: the whole card is clickable; contact buttons sit above it --}}
                <a href="{{ $url }}" class="transition-colors group-hover:text-brand-700 after:absolute after:inset-0 after:z-10 after:rounded-[1.75rem] focus-visible:outline-none focus-visible:after:ring-2 focus-visible:after:ring-brand-600">
                    {{ $expert->name }}
                </a>
            </h3>

            @if ($department)
                <p class="mt-1.5 flex items-center gap-1.5 text-[13.5px] muted">
                    <x-ui-icon name="building" class="h-4 w-4 shrink-0 text-ink-400" />
                    <span class="truncate">{{ $department }}</span>
                </p>
            @endif

            @if ($tags)
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ($tags as $tag)
                        <span class="rounded-full border border-brand-100 bg-brand-50 px-2.5 py-1 text-[11.5px] font-medium text-brand-700">{{ $tag }}</span>
                    @endforeach
                    @if ($moreTags)
                        <span class="rounded-full border border-ink-200 px-2.5 py-1 text-[11.5px] font-medium muted">+{{ $moreTags }}</span>
                    @endif
                </div>
            @endif

            <div class="min-h-5 flex-1"></div>

            {{-- Footer: contact + profile --}}
            <div class="flex items-center justify-between gap-3 border-t border-ink-100 pt-4">
                <div class="flex items-center gap-1.5">
                    @if ($expert->email)
                        <a href="mailto:{{ $expert->email }}" aria-label="Email {{ $expert->name }}" title="{{ $expert->email }}" class="{{ $iconBtn }}">
                            <x-ui-icon name="mail" class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($expert->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $expert->phone) }}" aria-label="Call {{ $expert->name }}" class="{{ $iconBtn }}">
                            <x-ui-icon name="phone" class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($expert->linkedin)
                        <a href="{{ $expert->linkedin }}" target="_blank" rel="noopener" aria-label="{{ $expert->name }} on LinkedIn" class="{{ $iconBtn }}">
                            <x-ui-icon name="linkedin" class="h-4 w-4" />
                        </a>
                    @endif
                    @if ($expert->scholar)
                        <a href="{{ $expert->scholar }}" target="_blank" rel="noopener" aria-label="{{ $expert->name }} on Google Scholar" class="{{ $iconBtn }}">
                            <x-ui-icon name="academic" class="h-4 w-4" />
                        </a>
                    @endif
                </div>

                <span class="inline-flex items-center gap-2 text-[13px] font-semibold text-ink-900 transition-colors group-hover:text-brand-700" aria-hidden="true">
                    View profile
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-950 text-white transition duration-500 {{ $ease }} group-hover:rotate-45 group-hover:bg-brand-600">
                        <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                    </span>
                </span>
            </div>
        </div>
    </article>
</div>
