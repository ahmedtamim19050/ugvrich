@props(['expert', 'index' => 0])

<a href="{{ route('experts.show', $expert) }}"
   class="card card-hover group flex flex-col reveal"
   style="transition-delay: {{ min($index * 60, 300) }}ms">

    <div class="flex items-start gap-4">
        @if ($expert->photo)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($expert->photo) }}" alt="{{ $expert->name }}"
                 loading="lazy" class="h-16 w-16 shrink-0 rounded-2xl object-cover">
        @else
            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl
                         bg-brand-600 font-display text-lg font-bold text-white">
                {{ $expert->initials }}
            </span>
        @endif

        <div class="min-w-0">
            <h3 class="font-display text-[17px] font-bold leading-snug transition group-hover:text-brand-700">
                {{ $expert->name }}
            </h3>
            <p class="mt-1 font-medium text-brand-600">{{ $expert->designation }}</p>
            <p class="mt-0.5 text-[13px] muted">{{ $expert->department }}</p>
        </div>
    </div>

    @if ($expert->expertise)
        <div class="mt-5 flex flex-wrap gap-1.5">
            @foreach (array_slice($expert->expertise, 0, 3) as $tag)
                <span class="rounded-full border hairline px-2.5 py-1 text-[11.5px] font-medium muted">{{ $tag }}</span>
            @endforeach
        </div>
    @endif

    @if ($expert->research_interests)
        <p class="mt-4 text-[13.5px] leading-relaxed muted">
            {{ \Illuminate\Support\Str::limit($expert->research_interests, 110) }}
        </p>
    @endif

    <span class="text-[13px] mt-auto flex items-center gap-2 pt-6 font-semibold text-brand-600">
        {{ __('site.cards.view_profile') }}
        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
    </span>
</a>
