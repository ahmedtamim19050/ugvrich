@if ($projects->isNotEmpty())
    <section class="py-24 bg-ink-50 sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    :eyebrow="__('site.home.projects_eyebrow')"
                    :title="__('site.home.projects_title')"
                    :lead="__('site.home.projects_lead')" />

                <div class="reveal flex shrink-0 flex-wrap gap-3">
                    <a href="{{ route('innovation.index') }}" class="btn-primary">
                        Innovation Wing <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                    <a href="{{ route('projects.index') }}" class="btn-ghost">{{ __('site.actions.all_projects') }}</a>
                </div>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 {{ $projects->count() >= 5 ? 'lg:grid-cols-4 lg:grid-rows-2' : 'lg:grid-cols-3 lg:grid-rows-2' }}">
                @foreach ($projects as $i => $project)
                    <x-cards.project-image-card :project="$project" :index="$i"
                        :featured="$loop->first"
                        :class="$loop->first ? 'md:col-span-2 lg:row-span-2' : 'min-h-[20rem]'" />
                @endforeach
            </div>
        </div>
    </section>
@endif
