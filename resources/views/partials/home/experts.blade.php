@if ($experts->isNotEmpty())
    <section class="py-24 bg-white sm:py-28">
        <div class="container-rich">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <x-section-heading
                    :eyebrow="__('site.home.experts_eyebrow')"
                    :title="__('site.home.experts_title')"
                    :lead="__('site.home.experts_lead')" />

                <a href="{{ route('experts.index') }}" class="btn-ghost reveal shrink-0">
                    {{ __('site.home.experts_link') }} <x-ui-icon name="search" class="h-4 w-4" />
                </a>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7">
                @foreach ($experts as $i => $expert)
                    <x-cards.expert-profile-card :expert="$expert" :index="$i" />
                @endforeach
            </div>
        </div>
    </section>
@endif
