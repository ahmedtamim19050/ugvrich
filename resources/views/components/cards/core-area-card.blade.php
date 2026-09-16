@props(['area', 'index' => 0])

@php
    // An uploaded cover wins; otherwise the bundled photo for this pillar.
    $cover = $area->image
        ? Storage::url($area->image)
        : asset('media/pillars/'.$area->slug.'.jpg');
@endphp

<article class="pillar-card spotlight reveal group" style="transition-delay: {{ min($index * 90, 300) }}ms">

    {{-- Cover --}}
    <div class="relative aspect-[16/9] overflow-hidden">
        {{-- Two nested elements: the wrapper takes the scroll
             parallax, the image takes the settle and hover zoom. --}}
        <div class="pillar-media">
            <img src="{{ $cover }}" alt="" aria-hidden="true" loading="lazy" class="pillar-img">
        </div>

        {{-- Scrim, so the title stays readable over any photo. --}}
        <div class="pillar-scrim"></div>

        <span class="pillar-badge absolute right-5 top-5 rounded-full bg-white/15 px-3 py-1 font-display
                     text-[12px] font-bold text-white backdrop-blur-md ring-1 ring-inset ring-white/25">
            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
        </span>

        <div class="pillar-caption absolute inset-x-5 bottom-5 flex items-end gap-3.5">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[14px] bg-white text-brand-600
                         transition-transform duration-500 ease-out group-hover:scale-110">
                <x-ui-icon :name="$area->icon ?? 'sparkles'" class="h-5 w-5" />
            </span>
            <div class="min-w-0 flex-1">
                <h3 class="font-display text-[22px] font-bold leading-tight text-white">{{ $area->title }}</h3>
                <p class="mt-0.5 truncate text-[12.5px] font-medium text-white/75">{{ $area->tagline }}</p>
            </div>

            <span class="pillar-go shrink-0">
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
            </span>
        </div>
    </div>

    {{-- Body --}}
    <div class="flex flex-1 flex-col p-7">
        <p class="text-[15px] leading-relaxed muted">{{ $area->description }}</p>
        <span class="pillar-rule" aria-hidden="true"></span>

        @if ($area->items)
            <ul class="mt-6 grid gap-x-5 gap-y-2.5 sm:grid-cols-2">
                @foreach ($area->items as $item)
                    <li class="flex items-start gap-2.5 text-[13.5px] muted">
                        <x-ui-icon name="check" class="mt-[3px] h-3.5 w-3.5 shrink-0 text-brand-600" stroke="2.4" />
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</article>
