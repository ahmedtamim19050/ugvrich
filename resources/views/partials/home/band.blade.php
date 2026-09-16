@php
    $words = ['Research', 'Innovation', 'Consultancy', 'Knowledge Exchange'];
@endphp

{{-- A slow band of the four things RICH stands for. Purely decorative, so it
     is hidden from assistive tech and stops moving under reduced motion. --}}
<section class="overflow-hidden border-y hairline bg-white py-10 sm:py-12" aria-hidden="true">
    <div class="brand-band">
        {{-- Twice through, so the loop has no seam. --}}
        @foreach (range(1, 2) as $pass)
            @foreach ($words as $i => $word)
                <span class="brand-band-word {{ $i % 2 === 1 ? 'is-solid' : '' }}">{{ $word }}</span>
                <span class="brand-band-dot"></span>
            @endforeach
        @endforeach
    </div>
</section>
