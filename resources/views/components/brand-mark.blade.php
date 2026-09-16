@props(['href' => null, 'invert' => false])

<a href="{{ $href ?? route('home') }}" class="group flex items-center gap-3" aria-label="{{ $site->name() }} home">
    <span @class([
        'flex h-10 w-10 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-105',
        'bg-white text-brand-700' => $invert,
        'bg-brand-600 text-white' => ! $invert,
    ])>
        <span class="font-display text-[15px] font-bold leading-none tracking-tight">R</span>
    </span>

    <span class="flex flex-col leading-none">
        <span @class([
            'brand-word font-display text-[19px] font-bold tracking-tight',
            'text-white' => $invert,
            'text-ink-950' => ! $invert,
        ])>
            UGV <span @class(['brand-word-accent', 'text-white/70' => $invert, 'text-brand-600' => ! $invert])>RICH</span>
        </span>
        <span @class([
            'brand-sub mt-1 hidden whitespace-nowrap text-[10px] font-medium uppercase tracking-[0.14em] sm:block',
            'text-white/60' => $invert,
            'text-ink-400' => ! $invert,
        ])>
            Research · Innovation · Consultancy
        </span>
    </span>
</a>
