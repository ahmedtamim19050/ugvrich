<x-layouts.app :title="__('site.nav.projects')"
               :description="__('site.projects.meta_description')">

    <x-page-hero
        :eyebrow="__('site.nav.projects')"
        :title="__('site.projects.hero_title')"
        :lead="__('site.projects.hero_lead')"
        :breadcrumbs="[__('site.nav.projects') => null]" />

    <section class="py-16 bg-white sm:py-20">
        <div class="container-rich">
            {{-- Type tabs --}}
            @php
                $typeIcons = ['innovation' => 'lightbulb', 'research' => 'beaker', 'consultancy' => 'briefcase'];
            @endphp
            @if ($typeCounts->filter()->count() > 1)
            <div class="reveal flex gap-2 overflow-x-auto rounded-full border border-ink-100 bg-ink-50 p-1.5 [scrollbar-width:none] sm:inline-flex">
                <a href="{{ route('projects.index') }}"
                   @class([
                       'inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2.5 text-[14px] font-semibold transition',
                       'bg-ink-950 text-white shadow' => ! $type,
                       'text-ink-600 hover:bg-white hover:text-ink-950' => $type,
                   ])>
                    {{ __('site.projects.filter_all') }}
                    <span @class(['rounded-full px-1.5 text-[11.5px] tabular-nums', 'bg-white/15' => ! $type, 'bg-ink-200/60' => $type])>{{ $typeCounts->sum() }}</span>
                </a>
                @foreach (\App\Support\Vocabulary::all('project_types') as $key => $label)
                    <a href="{{ route('projects.index', ['type' => $key]) }}"
                       @class([
                           'inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2.5 text-[14px] font-semibold transition',
                           'bg-brand-600 text-white shadow' => $type === $key,
                           'text-ink-600 hover:bg-white hover:text-ink-950' => $type !== $key,
                       ])>
                        <x-ui-icon :name="$typeIcons[$key] ?? 'grid'" class="h-4 w-4" />
                        {{ $label }}
                        <span @class(['rounded-full px-1.5 text-[11.5px] tabular-nums', 'bg-white/20' => $type === $key, 'bg-ink-200/60' => $type !== $key])>{{ $typeCounts[$key] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
            @endif

            {{-- Consultancy areas (consultancy view only) --}}
            @if ($type === 'consultancy' || request('area'))
                <div class="reveal mt-4 flex flex-wrap items-center gap-2">
                    <a href="{{ route('projects.index', ['type' => 'consultancy']) }}"
                       @class([
                           'rounded-full border px-3.5 py-1.5 text-[13px] font-medium transition',
                           'border-brand-600 bg-brand-50 text-brand-700' => ! request('area'),
                           'border-ink-200 text-ink-600 hover:border-brand-300 hover:text-brand-700' => request('area'),
                       ])>{{ __('site.projects.all_areas') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('projects.index', ['type' => 'consultancy', 'area' => $category->slug]) }}"
                           @class([
                               'rounded-full border px-3.5 py-1.5 text-[13px] font-medium transition',
                               'border-brand-600 bg-brand-50 text-brand-700' => request('area') === $category->slug,
                               'border-ink-200 text-ink-600 hover:border-brand-300 hover:text-brand-700' => request('area') !== $category->slug,
                           ])>{{ Str::before($category->name, ' &') }}</a>
                    @endforeach
                </div>
            @endif

            <p class="mt-6 text-[14px] muted">
                {{ trans_choice('site.projects.showing', $projects->total(), ['shown' => $projects->count(), 'total' => $projects->total()]) }}
            </p>

            @if ($projects->isEmpty())
                <div class="mt-10 rounded-3xl border border-dashed hairline p-14 text-center">
                    <x-ui-icon name="briefcase" class="mx-auto h-9 w-9 muted" stroke="1.3" />
                    <p class="mt-4 font-display text-lg font-semibold">{{ __('site.projects.empty_title') }}</p>
                    <p class="mt-2 text-[14.5px] muted">{{ __('site.projects.empty_body') }}</p>
                    <a href="{{ route('projects.index') }}" class="btn-ghost mt-6">{{ __('site.actions.clear_filter') }}</a>
                </div>
            @else
                <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $i => $project)
                        <x-cards.project-image-card :project="$project" :index="$i" class="min-h-[24rem]" />
                    @endforeach
                </div>

                <div class="mt-14 border-t border-ink-100 pt-8">{{ $projects->links('vendor.pagination.rich') }}</div>
            @endif
        </div>
    </section>

</x-layouts.app>
