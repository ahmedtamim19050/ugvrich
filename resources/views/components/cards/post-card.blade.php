@props(['post', 'index' => 0])

<a href="{{ route('news.show', $post) }}"
   class="border group relative flex flex-col overflow-hidden rounded-3xl transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_70px_-34px_rgba(31,66,245,0.45)] border-ink-200 bg-white hover:border-brand-300 reveal"
   style="transition-delay: {{ min($index * 80, 320) }}ms">

    <x-media-frame :src="$post->image" :alt="$post->title" :seed="$post->slug"
                   :icon="$post->type === 'event' ? 'calendar' : 'document'" ratio="aspect-[16/9]" />

    <div class="flex flex-1 flex-col p-7">
        <div class="flex flex-wrap items-center gap-2 text-[12px]">
            <span @class([
                'rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.1em]',
                'bg-brand-50 text-brand-700' => $post->type === 'event',
                'bg-ink-100 text-ink-600' => $post->type !== 'event',
            ])>
                {{ $post->category ?: ucfirst($post->type) }}
            </span>

            @if ($post->type === 'event' && $post->event_at)
                <span class="flex items-center gap-1.5 muted">
                    <x-ui-icon name="calendar" class="h-3.5 w-3.5" />{{ $post->event_at->format('j M Y') }}
                </span>
            @elseif ($post->published_at)
                <span class="muted">{{ $post->published_at->format('j M Y') }}</span>
            @endif
        </div>

        <h3 class="mt-4 font-display text-[18px] font-bold leading-snug transition group-hover:text-brand-700">
            {{ $post->title }}
        </h3>

        <p class="mt-3 text-[14.5px] leading-relaxed muted">
            {{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}
        </p>

        <span class="text-[13px] mt-auto flex items-center gap-2 pt-6 font-semibold text-brand-600">
            {{ __('site.cards.read_more') }}
            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
        </span>
    </div>
</a>
