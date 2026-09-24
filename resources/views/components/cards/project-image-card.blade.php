@props(['project', 'index' => 0, 'featured' => false])

{{-- The scroll-reveal lives on the wrapper so its transform and delay never fight the card's hover. --}}
<div {{ $attributes->merge(['class' => 'reveal flex']) }} style="transition-delay: {{ min($index * 90, 360) }}ms">
<a href="{{ route('projects.show', $project) }}"
   class="group relative isolate flex w-full overflow-hidden rounded-3xl bg-ink-900 shadow-[0_10px_30px_-20px_rgba(11,15,24,0.4)] transition-[transform,box-shadow] duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] will-change-transform hover:-translate-y-1.5 hover:shadow-[0_30px_60px_-28px_rgba(11,15,24,0.55)] focus-visible:-translate-y-1.5 {{ $featured ? 'min-h-[26rem] lg:min-h-[36rem]' : 'min-h-[22rem] lg:min-h-[17rem]' }}">

    {{-- Full-bleed image --}}
    <div class="absolute inset-0 -z-10">
        <x-media-frame :src="$project->image" :alt="$project->title" :seed="$project->slug"
                       icon="briefcase" ratio="h-full w-full" />
    </div>

    {{-- Legibility scrim --}}
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950/95 via-ink-950/45 to-ink-950/5 " aria-hidden="true"></div>

    {{-- Top row: area + stage (innovation) or area + year (consultancy) --}}
    @php
        $areaLabel = $project->innovationArea
            ? \Illuminate\Support\Str::before($project->innovationArea->name, ' Innovation')
            : ($project->category ? \Illuminate\Support\Str::before($project->category->name, ' &') : null);
    @endphp
    <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-5 sm:p-6">
        @if ($areaLabel)
            <span class="rounded-full border border-white/20 bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white backdrop-blur-md">
                {{ $areaLabel }}
            </span>
        @endif
        @if ($project->stage_label)
            <span class="ml-auto inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-[11.5px] font-bold text-brand-700">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-brand-600"></span>{{ $project->stage_label }}
            </span>
        @elseif ($project->year)
            <span class="ml-auto rounded-full bg-white px-3 py-1 text-[12px] font-bold text-ink-900">
                {{ $project->year }}
            </span>
        @endif
    </div>

    {{-- Bottom content --}}
    <div class="mt-auto w-full p-6 sm:p-8">
        @if ($project->client || $project->department)
            <p class="flex items-center gap-2 text-[12px] font-medium uppercase tracking-[0.14em] text-white/70">
                <span class="h-px w-6 bg-brand-400"></span>
                <span class="truncate">{{ $project->client ?: $project->department_name }}</span>
            </p>
        @endif

        <h3 class="mt-3 font-display font-bold leading-snug !text-white {{ $featured ? 'text-2xl sm:text-3xl' : 'line-clamp-2 text-lg sm:text-xl' }}">
            {{ $project->title }}
        </h3>

        @if ($featured)
            <p class="mt-3 max-w-xl text-[14.5px] leading-relaxed text-white/75">
                {{ \Illuminate\Support\Str::limit($project->summary, 180) }}
            </p>
        @endif

        <div class="mt-5 flex items-center justify-between gap-4 border-t border-white/15 pt-5">
            <span class="text-[13px] font-semibold text-white">{{ $project->type === 'innovation' ? __('site.cards.view_innovation') : __('site.cards.view_case_study') }}</span>
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-ink-900 transition duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:rotate-45 group-hover:bg-brand-600 group-hover:text-white">
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
            </span>
        </div>
    </div>
</a>
</div>
