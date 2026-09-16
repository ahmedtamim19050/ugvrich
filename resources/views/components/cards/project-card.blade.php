@props(['project', 'index' => 0])

<a href="{{ route('projects.show', $project) }}"
   class="border group relative flex flex-col overflow-hidden rounded-3xl transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_70px_-34px_rgba(31,66,245,0.45)] border-ink-200 bg-white hover:border-brand-300 reveal"
   style="transition-delay: {{ min($index * 80, 320) }}ms">

    <x-media-frame :src="$project->image" :alt="$project->title" :seed="$project->slug"
                   icon="briefcase" ratio="aspect-[16/11]" />

    <div class="flex flex-1 flex-col p-7">
        <div class="flex flex-wrap items-center gap-2">
            @if ($project->category)
                <span class="text-[11px] rounded-full px-3 py-1 font-semibold uppercase tracking-[0.1em] bg-brand-50 text-brand-600">
                    {{ \Illuminate\Support\Str::before($project->category->name, ' &') }}
                </span>
            @endif
            @if ($project->year)
                <span class="text-[12px] font-medium muted">{{ $project->year }}</span>
            @endif
        </div>

        <h3 class="mt-4 font-display text-[19px] font-bold leading-snug transition group-hover:text-brand-700">
            {{ $project->title }}
        </h3>

        <p class="mt-3 text-[14.5px] leading-relaxed muted">
            {{ \Illuminate\Support\Str::limit($project->summary, 130) }}
        </p>

        <div class="mt-auto flex items-center justify-between gap-4 border-t hairline pt-5 mt-6">
            <span class="min-w-0 truncate text-[13px] muted">{{ $project->client }}</span>
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border hairline text-ink-500 transition group-hover:border-brand-600 group-hover:bg-brand-600 group-hover:text-white">
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
            </span>
        </div>
    </div>
</a>
