@props(['category', 'index' => 0])

@php
    $services = $category->relationLoaded('services') ? $category->services : collect();
    $shown = $services->take(4);
    $remaining = max(0, $services->count() - $shown->count());

    // Uploaded image wins; otherwise the bundled photo for this area.
    $media = $category->image
        ? Storage::url($category->image)
        : asset('media/services/'.$category->slug.'.jpg');
@endphp

<a href="{{ route('services.show', $category) }}"
   class="service-card spotlight group reveal"
   style="transition-delay: {{ min($index * 80, 320) }}ms">

    <img src="{{ $media }}" alt="" aria-hidden="true" loading="lazy" class="service-media">

    <span class="service-index" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>

    <span class="relative service-icon">
        <x-ui-icon :name="$category->icon ?? 'grid'" class="h-6 w-6" />
    </span>

    <h3 class="relative mt-6 max-w-[85%] font-display text-[19px] font-bold leading-snug">
        {{ $category->name }}
    </h3>

    <p class="relative mt-2 text-[14px] leading-relaxed muted">{{ $category->tagline }}</p>

    <span class="relative service-rule" aria-hidden="true"></span>

    @if ($shown->isNotEmpty())
        <span class="relative flex flex-wrap gap-2">
            @foreach ($shown as $service)
                <span class="service-chip">{{ $service->name }}</span>
            @endforeach

            @if ($remaining > 0)
                <span class="service-chip-more">+{{ $remaining }} more</span>
            @endif
        </span>
    @endif

    <span class="relative mt-auto flex items-center gap-2 pt-7 text-[13.5px] font-semibold text-brand-700">
        Explore area
        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1.5" />
    </span>
</a>
