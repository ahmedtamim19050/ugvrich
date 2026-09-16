@props([
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'align' => 'left',      // left | center
    'invert' => false,
    'size' => 'md',         // md | lg
])

<div @class([
    'reveal',
    'max-w-3xl' => $align === 'left',
    'mx-auto max-w-3xl text-center' => $align === 'center',
])>
    @if ($eyebrow)
        <span @class([
            'eyebrow',
            'eyebrow-invert' => $invert,
        ])>
            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
            {{ $eyebrow }}
        </span>
    @endif

    @if ($title)
        <h2 data-split @class([
            'mt-5 font-display font-bold leading-[1.1] tracking-tight',
            'text-3xl sm:text-4xl lg:text-[42px]' => $size === 'md',
            'text-4xl sm:text-5xl lg:text-[54px]' => $size === 'lg',
            'text-white' => $invert,
        ])>
            {!! $title !!}
        </h2>
    @endif

    @if ($lead)
        <p @class([
            'mt-5 text-[16.5px] leading-[1.75]',
            'text-white/80' => $invert,
            'muted' => ! $invert,
            'mx-auto' => $align === 'center',
        ])>
            {{ $lead }}
        </p>
    @endif

    {{ $slot }}
</div>
