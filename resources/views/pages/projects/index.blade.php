<x-layouts.app title="Projects & Achievements"
               description="Consultancy and research assignments delivered by UGV RICH for government, industry, NGOs and academic partners.">

    <x-page-hero
        eyebrow="Projects & achievements"
        title='Work that changed a <span class="text-accent">decision</span>'
        lead="A record of consultancy and research assignments delivered by UGV RICH, with the client, duration, area of consultancy and outcome for each."
        :breadcrumbs="['Projects' => null]" />

    <section class="py-16 bg-white sm:py-20">
        <div class="container-rich">
            {{-- Filter --}}
            <div class="reveal flex flex-wrap items-center gap-2.5">
                <a href="{{ route('projects.index') }}"
                   @class([
                       'rounded-full border px-4 py-2 text-[13.5px] font-medium transition',
                       'border-brand-600 bg-brand-600 text-white' => ! request('area'),
                       'hairline muted hover:border-brand-300 hover:text-brand-700' => request('area'),
                   ])>
                    All areas
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['area' => $category->slug]) }}"
                       @class([
                           'rounded-full border px-4 py-2 text-[13.5px] font-medium transition',
                           'border-brand-600 bg-brand-600 text-white' => request('area') === $category->slug,
                           'hairline muted hover:border-brand-300 hover:text-brand-700' => request('area') !== $category->slug,
                       ])>
                        {{ Str::before($category->name, ' &') }}
                    </a>
                @endforeach
            </div>

            <p class="mt-6 text-[14px] muted">
                Showing {{ $projects->count() }} of {{ $projects->total() }} {{ Str::plural('project', $projects->total()) }}
            </p>

            @if ($projects->isEmpty())
                <div class="mt-10 rounded-3xl border border-dashed hairline p-14 text-center">
                    <x-ui-icon name="briefcase" class="mx-auto h-9 w-9 muted" stroke="1.3" />
                    <p class="mt-4 font-display text-lg font-semibold">No projects in this area yet</p>
                    <p class="mt-2 text-[14.5px] muted">Try another area, or browse all projects.</p>
                    <a href="{{ route('projects.index') }}" class="btn-ghost mt-6">Clear filter</a>
                </div>
            @else
                <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $i => $project)
                        <x-cards.project-card :project="$project" :index="$i" />
                    @endforeach
                </div>

                <div class="mt-14 border-t border-ink-100 pt-8">{{ $projects->links('vendor.pagination.rich') }}</div>
            @endif
        </div>
    </section>

    @include('partials.home.cta')
</x-layouts.app>
