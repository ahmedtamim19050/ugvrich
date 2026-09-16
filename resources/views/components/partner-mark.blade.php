@props(['partner', 'size' => 'md'])

@php
    /*
     * A monogram that stands in for a partner logo.
     *
     * Repeating one generic building icon across every partner reads as
     * unfinished; initials on a coloured tile read as a logo. Deterministic,
     * so an organisation always gets the same mark.
     *
     * Uploading a real logo on the record replaces this.
     */
    $skip = ['of', 'and', 'the', 'for', 'de', 'da'];

    $words = collect(preg_split('/[\s\-]+/', (string) $partner->name))
        ->filter(fn ($w) => $w !== '' && ! in_array(mb_strtolower($w), $skip, true))
        ->values();

    $initials = mb_strtoupper(
        mb_substr($words->first() ?? '?', 0, 1).mb_substr($words->get(1) ?? '', 0, 1)
    );

    // Brand shades only. Enough variation to read as separate logos, without
    // introducing a second colour into a single-accent palette.
    $tints = ['bg-brand-500', 'bg-brand-600', 'bg-brand-700', 'bg-brand-800'];
    $tint = $tints[crc32((string) $partner->name) % count($tints)];

    $box = $size === 'lg' ? 'h-11 w-11 text-[14px] rounded-[14px]' : 'h-10 w-10 text-[13px] rounded-xl';
@endphp

@if ($partner->logo)
    <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}"
         {{ $attributes->merge(['class' => $size === 'lg' ? 'partner-mark h-11 w-11 rounded-[14px] object-contain' : 'partner-mark h-10 w-10 rounded-xl object-contain']) }}>
@else
    <span {{ $attributes->merge(['class' => "partner-mark flex shrink-0 items-center justify-center font-display font-bold text-white $tint $box"]) }}
          aria-hidden="true">
        {{ $initials }}
    </span>
@endif
