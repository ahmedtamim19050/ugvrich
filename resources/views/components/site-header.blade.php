@props(['overlay' => false])

@php
    $categories = $site->navCategories();

    $links = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Services', 'route' => 'services.index', 'mega' => true],
        ['label' => 'Research', 'route' => 'research'],
        ['label' => 'Projects', 'route' => 'projects.index'],
        ['label' => 'Experts', 'route' => 'experts.index'],
        ['label' => 'News & Events', 'route' => 'news.index'],
    ];
@endphp

{{--
    On the home page the header is attached to the hero: it sits on top of the
    animated background and only takes on a surface once you scroll past it.
    Everywhere else it is a normal sticky bar.
--}}
<header
    x-data="siteHeader({{ $overlay ? 'true' : 'false' }})"
    x-init="init()"
    @scroll.window="onScroll()"
    {{-- `is-clear` only ever appears in overlay mode, so CSS uses it to mean
         "sitting on the hero video" and flips the bar to light text. --}}
    :class="clear ? 'is-clear' : 'is-solid'"
    @class([
        'z-50 animate-header-in',
        'fixed inset-x-0 top-0' => $overlay,
        'sticky top-0' => ! $overlay,
    ])>

    {{-- Utility bar: slides away once you start scrolling --}}
    <div class="overflow-hidden bg-ink-950 text-ink-300 transition-all duration-500 ease-out"
         :class="solid ? 'max-h-0 opacity-0' : 'max-h-12 opacity-100'">
        <div class="container-rich hidden h-11 items-center justify-between text-[13px] lg:flex">
            <p class="flex items-center gap-2">
                <x-ui-icon name="sparkles" class="h-4 w-4 text-brand-400" />
                <span>{{ $site->get('site_motto') }}</span>
            </p>

            <div class="flex items-center gap-6">
                @if ($phone = $site->get('contact_phone'))
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}"
                       class="group flex items-center gap-2 transition hover:text-white">
                        <x-ui-icon name="phone" class="h-4 w-4 text-brand-400 transition-transform duration-300 group-hover:-rotate-12" />{{ $phone }}
                    </a>
                @endif
                @if ($email = $site->get('contact_email'))
                    <a href="mailto:{{ $email }}" class="group flex items-center gap-2 transition hover:text-white">
                        <x-ui-icon name="mail" class="h-4 w-4 text-brand-400 transition-transform duration-300 group-hover:-translate-y-0.5" />{{ $email }}
                    </a>
                @endif

                <div class="flex items-center gap-3 border-l border-white/15 pl-6">
                    @foreach ($site->socials() as $icon => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           aria-label="{{ str_replace('-social', '', $icon) }}"
                           class="transition duration-300 hover:-translate-y-0.5 hover:text-white">
                            <x-ui-icon :name="$icon" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Main bar --}}
    <div class="border-b transition-all duration-500 ease-out"
         :class="! clear
            ? 'border-ink-200 bg-white shadow-[0_10px_30px_-24px_rgba(11,15,24,0.5)] backdrop-blur-xl'
            : 'border-transparent bg-transparent'">

        <div class="container-rich flex items-center justify-between gap-6 transition-all duration-500"
             :class="solid ? 'h-[66px]' : 'h-[78px]'">

            <x-brand-mark />

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Primary">
                @foreach ($links as $i => $link)
                    @php
                        $active = request()->routeIs($link['route'])
                            || ($link['route'] === 'services.index' && request()->routeIs('services.*'));
                    @endphp

                    @if ($link['mega'] ?? false)
                        <div class="relative animate-nav-in" style="animation-delay: {{ 120 + $i * 60 }}ms"
                             @mouseenter="mega = true" @mouseleave="mega = false">
                            <a href="{{ route($link['route']) }}" class="nav-link flex items-center gap-1.5"
                               data-active="{{ $active ? 'true' : 'false' }}">
                                {{ $link['label'] }}
                                <x-ui-icon name="chevron-down" class="h-3.5 w-3.5 transition-transform duration-300"
                                           ::class="mega && 'rotate-180'" />
                            </a>

                            <div x-show="mega" x-cloak
                                 x-transition:enter="transition duration-250 ease-out"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition duration-150 ease-in"
                                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                 class="absolute left-1/2 top-full z-40 w-[660px] -translate-x-1/2 pt-4">

                                <div class="overflow-hidden rounded-3xl border border-ink-200 bg-white p-3
                                            shadow-[0_40px_80px_-40px_rgba(11,15,24,0.35)]">
                                    <div class="grid gap-1 sm:grid-cols-2">
                                        @foreach ($categories as $j => $category)
                                            <a href="{{ route('services.show', $category) }}"
                                               x-show="mega"
                                               x-transition:enter="transition duration-300 ease-out"
                                               x-transition:enter-start="opacity-0 translate-y-2"
                                               x-transition:enter-end="opacity-100 translate-y-0"
                                               style="transition-delay: {{ $j * 45 }}ms"
                                               class="group flex items-start gap-3 rounded-2xl p-3 transition hover:bg-ink-50">
                                                <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                                             bg-brand-50 text-brand-600 transition duration-300
                                                             group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white">
                                                    <x-ui-icon :name="$category->icon ?? 'grid'" class="h-4.5 w-4.5" />
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block text-[13.5px] font-semibold leading-snug text-ink-950">
                                                        {{ $category->name }}
                                                    </span>
                                                    <span class="mt-0.5 block truncate text-xs muted">{{ $category->tagline }}</span>
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>

                                    <a href="{{ route('services.index') }}"
                                       class="group mt-2 flex items-center justify-between rounded-2xl bg-brand-50 px-4 py-3
                                              text-sm font-semibold text-brand-700 transition hover:bg-brand-600 hover:text-white">
                                        Browse the full consultancy catalogue
                                        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route($link['route']) }}"
                           class="nav-link animate-nav-in"
                           style="animation-delay: {{ 120 + $i * 60 }}ms"
                           data-active="{{ $active ? 'true' : 'false' }}">
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('contact') }}"
                   class="btn-primary group hidden animate-nav-in px-4.5 py-2.5 text-[13px] sm:inline-flex"
                   style="animation-delay: 560ms">
                    Request consultancy
                    <x-ui-icon name="arrow-up-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </a>

                <button type="button" @click="open = true"
                        class="menu-button flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 bg-white
                               text-ink-800 transition duration-300 hover:border-brand-300 hover:text-brand-700 xl:hidden"
                        aria-label="Open menu">
                    <x-ui-icon name="menu" class="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- Scroll progress --}}
        <div class="relative h-0.5 w-full" aria-hidden="true">
            <div class="h-0.5 origin-left bg-brand-600 transition-transform duration-150 ease-out"
                 :style="`transform: scaleX(${progress})`"></div>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-[60] xl:hidden">
        <div x-show="open" x-transition.opacity @click="open = false"
             class="absolute inset-0 bg-ink-950/40 backdrop-blur-sm"></div>

        <div x-show="open" x-cloak
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition duration-200 ease-in"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="absolute right-0 top-0 flex h-full w-[88%] max-w-sm flex-col overflow-y-auto border-l border-ink-200 bg-white p-6">

            <div class="flex items-center justify-between">
                <x-brand-mark />
                <button type="button" @click="open = false"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-800"
                        aria-label="Close menu">
                    <x-ui-icon name="x" class="h-5 w-5" />
                </button>
            </div>

            <nav class="mt-8 flex flex-col gap-1" aria-label="Mobile">
                @foreach ($links as $i => $link)
                    <a href="{{ route($link['route']) }}"
                       x-show="open"
                       x-transition:enter="transition duration-300 ease-out"
                       x-transition:enter-start="opacity-0 translate-x-6"
                       x-transition:enter-end="opacity-100 translate-x-0"
                       style="transition-delay: {{ 60 + $i * 45 }}ms"
                       class="rounded-2xl px-4 py-3 font-display text-lg font-semibold text-ink-950 transition hover:bg-ink-50 hover:text-brand-700">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-6 rounded-3xl border border-ink-200 bg-ink-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] muted">Consultancy Areas</p>
                <div class="mt-3 flex flex-col gap-2">
                    @foreach ($categories as $category)
                        <a href="{{ route('services.show', $category) }}"
                           class="flex items-center gap-2.5 text-sm text-ink-600 transition hover:text-brand-700">
                            <x-ui-icon :name="$category->icon ?? 'grid'" class="h-4 w-4 shrink-0 text-brand-600" />
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('contact') }}" class="btn-primary mt-6 w-full">
                Request consultancy
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
            </a>

            <div class="mt-auto pt-8 text-sm muted">
                @if ($email = $site->get('contact_email'))
                    <a href="mailto:{{ $email }}" class="block transition hover:text-brand-700">{{ $email }}</a>
                @endif
                @if ($phone = $site->get('contact_phone'))
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="mt-1 block transition hover:text-brand-700">{{ $phone }}</a>
                @endif
            </div>
        </div>
    </div>
</header>
