@props(['post', 'index' => 0])

@php
    $isEvent = $post->type === 'event';
    $upcoming = $isEvent && $post->event_at && $post->event_at->isFuture();
    $date = $isEvent ? $post->event_at : $post->published_at;
    $ease = 'ease-[cubic-bezier(0.22,1,0.36,1)]';
@endphp

{{-- The scroll-reveal lives on the wrapper so its transform and delay never fight the card's hover. --}}
<div {{ $attributes->merge(['class' => 'reveal']) }} style="transition-delay: {{ min($index * 90, 360) }}ms">
    <article class="group relative flex h-full flex-col rounded-[1.75rem] border border-ink-100 bg-white p-2 shadow-[0_8px_24px_-18px_rgba(11,15,24,0.3)] transition-[transform,box-shadow,border-color] duration-500 {{ $ease }} hover:-translate-y-1.5 hover:border-brand-100 hover:shadow-[0_32px_60px_-30px_rgba(2,34,81,0.4)]">

        {{-- Image --}}
        <div class="relative isolate aspect-[16/11] overflow-hidden rounded-[1.4rem] bg-ink-100">
            <div class="absolute inset-0 -z-10">
                <x-media-frame :src="$post->image" :alt="$post->title" :seed="$post->slug"
                               :icon="$isEvent ? 'calendar' : 'document'" ratio="h-full w-full" />
            </div>

            <div class="absolute inset-x-0 top-0 -z-[5] h-1/2 bg-gradient-to-b from-ink-950/35 to-transparent" aria-hidden="true"></div>

            {{-- Type --}}
            <div class="absolute left-3 top-3 flex flex-wrap items-center gap-1.5">
                <span @class([
                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.1em] backdrop-blur-md',
                    'bg-brand-600 text-white' => $isEvent,
                    'bg-white/90 text-ink-900' => ! $isEvent,
                ])>
                    <x-ui-icon :name="$isEvent ? 'calendar' : 'document'" class="h-3.5 w-3.5" />
                    {{ $isEvent ? 'Event' : 'News' }}
                </span>
                @if ($upcoming)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.1em] text-brand-700 backdrop-blur-md">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-500 opacity-60"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                        </span>
                        Upcoming
                    </span>
                @endif
            </div>

            {{-- Calendar tile --}}
            @if ($date)
                <div class="absolute right-3 top-3 flex w-14 flex-col items-center overflow-hidden rounded-2xl bg-white text-center shadow-[0_10px_24px_-12px_rgba(11,15,24,0.45)]">
                    <span @class(['w-full py-1 text-[10px] font-bold uppercase tracking-[0.14em]', 'bg-brand-600 text-white' => $isEvent, 'bg-ink-900 text-white' => ! $isEvent])>
                        {{ $date->format('M') }}
                    </span>
                    <span class="py-1.5 font-display text-xl font-bold leading-none text-ink-950">{{ $date->format('d') }}</span>
                </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="flex flex-1 flex-col px-4 pb-4 pt-5">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[12.5px] muted">
                @if ($post->category)
                    <span class="font-semibold text-brand-700">{{ $post->category }}</span>
                    <span class="h-1 w-1 rounded-full bg-ink-300"></span>
                @endif
                @if ($isEvent && $post->location)
                    <span class="inline-flex min-w-0 items-center gap-1.5">
                        <x-ui-icon name="map-pin" class="h-3.5 w-3.5 shrink-0" />
                        <span class="truncate">{{ $post->location }}</span>
                    </span>
                @elseif ($date)
                    <span>{{ $date->format('j M Y') }}</span>
                @endif
            </div>

            <h3 class="mt-3 line-clamp-2 font-display text-[19px] font-bold leading-snug transition-colors duration-300 group-hover:text-brand-700">
                <a href="{{ route('news.show', $post) }}" class="after:absolute after:inset-0 after:z-10 after:rounded-[1.75rem] focus-visible:outline-none focus-visible:after:ring-2 focus-visible:after:ring-brand-600">
                    {{ $post->title }}
                </a>
            </h3>

            @if ($post->excerpt)
                <p class="mt-2.5 line-clamp-2 text-[14.5px] leading-relaxed muted">{{ $post->excerpt }}</p>
            @endif

            <div class="min-h-5 flex-1"></div>

            <div class="flex items-center justify-between gap-3 border-t border-ink-100 pt-4">
                <span class="inline-flex items-center gap-1.5 text-[12.5px] muted">
                    @if ($isEvent && $post->event_at)
                        <x-ui-icon name="clock" class="h-3.5 w-3.5" />
                        {{ $post->event_at->format('D, j M · g:i A') }}
                    @elseif ($post->author)
                        By {{ $post->author }}
                    @else
                        {{ $isEvent ? 'Event details' : 'Read the story' }}
                    @endif
                </span>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-950 text-white transition duration-500 {{ $ease }} group-hover:rotate-45 group-hover:bg-brand-600" aria-hidden="true">
                    <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                </span>
            </div>
        </div>
    </article>
</div>
