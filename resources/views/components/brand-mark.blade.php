@props(['href' => null, 'invert' => false])

{{--
    The logo stands on its own — it carries the name inside the emblem.
    Its artwork is dark navy and green on transparency, so on dark surfaces
    it sits on a white chip rather than disappearing into them.

    Height always comes from the caller: `<x-brand-mark class="h-16" />`.
--}}
<a href="{{ $href ?? route('home') }}" x-data class="group inline-flex items-center" aria-label="{{ $site->name() }} home">
    <span {{ $attributes->class([
        'flex aspect-square shrink-0 items-center justify-center group-hover:scale-105',
        'rounded-2xl bg-white p-2 shadow-[0_8px_24px_-12px_rgba(0,0,0,0.6)]' => $invert,
    ])->merge(['class' => '']) }}>
        {{-- One turn on load; hovering restarts it by re-adding the class. --}}
        <img src="{{ asset('media/logo-mark.png') }}" alt="{{ $site->name() }}"
             width="256" height="249" class="brand-spin h-full w-full object-contain"
             @mouseenter="$el.classList.remove('brand-spin'); void $el.offsetWidth; $el.classList.add('brand-spin')">
    </span>
</a>
