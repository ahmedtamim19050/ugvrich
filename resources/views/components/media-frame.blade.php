@props([
    'src' => null,
    'alt' => '',
    'seed' => '',
    'icon' => 'grid',
    'ratio' => 'aspect-[16/10]',
])

<div {{ $attributes->merge(['class' => "relative overflow-hidden $ratio"]) }}>
    @if ($src)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($src) }}" alt="{{ $alt }}" loading="lazy"
             class="h-full w-full object-cover transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.05]">
    @else
        {{-- No photograph on the record: fall back to generated blueprint art. --}}
        <x-cover-art :seed="$seed ?: $alt"
                     class="h-full w-full transition duration-700 group-hover:scale-[1.04]" />
    @endif
</div>
