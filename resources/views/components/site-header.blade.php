@props([])

@php
    $categories = $site->navCategories();
    $areas = $site->innovationAreas();

    // Grouped navigation. `match` lists the route patterns that light a group up;
    // groups with a `panel` open the full-width panel under the bar.
    $links = [
        ['key' => 'home', 'label' => 'Home', 'route' => 'home', 'match' => ['home']],
        ['key' => 'about', 'label' => 'About', 'route' => 'about', 'match' => ['about', 'experts.*'], 'panel' => [
            'title' => 'About RICH',
            'text' => 'The Research, Innovation & Consultation Hub of UGV — the people and purpose behind the work.',
            'children' => [
                ['About RICH', route('about'), 'globe', 'Who we are, vision and mission'],
                ['Team', route('experts.index'), 'users', 'Researchers and experts'],
            ],
        ]],
        ['key' => 'research', 'label' => 'Research', 'route' => 'research', 'match' => ['research'], 'panel' => [
            'title' => 'Research',
            'text' => 'Funded grants, published papers and the core capability our faculty bring to them.',
            'children' => [
                ['Research', route('research'), 'beaker', 'Routes into research and core capability'],
                ['Publications', route('research').'#publications', 'document', 'Recent papers and funded grants'],
            ],
        ]],
        ['key' => 'innovation', 'label' => 'Innovation', 'route' => 'innovation.index', 'match' => ['innovation.*', 'startup', 'ideas.*'], 'panel' => [
            'title' => 'Innovation areas',
            'text' => 'Every department brings its own discipline to the Innovation Wing.',
            'children' => array_merge(
                $areas->map(fn ($area) => [
                    $area->name, route('innovation.index', ['area' => $area->slug]), $area->icon ?? 'lightbulb',
                    $area->department ? config('rich.departments.'.$area->department) : null,
                ])->all(),
                [['Startup & Incubation', route('startup'), 'rocket', 'From idea to enterprise']],
            ),
        ]],
        ['key' => 'projects', 'label' => 'Projects', 'route' => 'projects.index', 'match' => ['projects.*']],
        ['key' => 'consultancy', 'label' => 'Consultancy', 'route' => 'services.index', 'match' => ['services.*'], 'panel' => [
            'title' => 'Consultancy',
            'text' => 'Expert services for industry, government and development partners.',
            'children' => $categories->map(fn ($category) => [
                $category->name, route('services.show', $category), $category->icon ?? 'grid', $category->tagline,
            ])->all(),
        ]],
        ['key' => 'news', 'label' => 'News & Events', 'route' => 'news.index', 'match' => ['news.*']],
        ['key' => 'contact', 'label' => 'Contact', 'route' => 'contact', 'match' => ['contact', 'consultancy.*']],
    ];

    $email = $site->get('contact_email');
    $phone = $site->get('contact_phone');
@endphp

{{-- A white sticky bar on every page; it only tightens up once you scroll. --}}
<header
    x-data="siteHeader()"
    x-init="init()"
    @scroll.window="onScroll()"
    @keydown.escape.window="panel = null"
    class="sticky top-0 z-50 animate-header-in">

    <div class="relative" @mouseleave="leave()">

        <div class="header-accent h-[3px]" aria-hidden="true"></div>

        {{-- Info strip: folds away once the page scrolls --}}
        <div class="header-strip header-ease hidden overflow-hidden lg:block"
             :class="solid ? 'max-h-0 opacity-0' : 'max-h-10 opacity-100'">
            <div class="container-rich flex h-9 items-center justify-between text-[12.5px]">
                <div class="flex items-center gap-5">
                    @if ($email)
                        <a href="mailto:{{ $email }}" class="flex items-center gap-1.5 transition hover:opacity-100">
                            <x-ui-icon name="mail" class="h-3.5 w-3.5" /> {{ $email }}
                        </a>
                    @endif
                    @if ($phone)
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="flex items-center gap-1.5 transition hover:opacity-100">
                            <x-ui-icon name="phone" class="h-3.5 w-3.5" /> {{ $phone }}
                        </a>
                    @endif
                </div>
                <a href="{{ route('ideas.create') }}" class="group flex items-center gap-1.5 font-medium transition hover:opacity-100">
                    Have an idea? Submit it to RICH
                    <x-ui-icon name="arrow-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5" />
                </a>
            </div>
        </div>

        {{-- Main bar --}}
        <div class="header-bar header-ease border-b border-ink-100 bg-white"
             :class="solid ? 'shadow-[0_10px_30px_-26px_rgba(7,20,38,0.6)]' : 'shadow-none'">

            <div class="container-rich header-ease flex items-center justify-between gap-6"
                 :class="solid ? 'h-[76px]' : 'h-[100px]'">

                <x-brand-mark class="header-ease h-[82px]" ::style="`height: ${solid ? 60 : 82}px`" />

                {{-- Desktop nav: plain text links over one sliding highlight pill --}}
                <nav x-ref="nav" class="relative hidden h-full items-stretch xl:flex" aria-label="Primary"
                     @mouseleave="settle()">
                    @foreach ($links as $i => $link)
                        @php $active = request()->routeIs(...$link['match']); @endphp

                        <a href="{{ route($link['route']) }}"
                           class="nav-item animate-nav-in"
                           style="animation-delay: {{ 120 + $i * 60 }}ms"
                           data-active="{{ $active ? 'true' : 'false' }}"
                           @if ($active) x-ref="current" @endif
                           @mouseenter="hover($el, {{ isset($link['panel']) ? "'{$link['key']}'" : 'null' }})"
                           @focus="hover($el, {{ isset($link['panel']) ? "'{$link['key']}'" : 'null' }})"
                           @if (isset($link['panel'])) :aria-expanded="panel === '{{ $link['key'] }}'" @endif>
                            {{ $link['label'] }}
                            @isset($link['panel'])
                                <x-ui-icon name="chevron-down" class="h-3.5 w-3.5 opacity-60 transition-transform duration-300"
                                           ::class="panel === '{{ $link['key'] }}' && 'rotate-180'" />
                            @endisset
                        </a>
                    @endforeach

                    <span class="nav-indicator" aria-hidden="true"
                          :style="`transform: translateX(${ind.left}px); width: ${ind.width}px; opacity: ${ind.width ? 1 : 0}`"></span>
                </nav>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('contact') }}"
                       class="btn-primary group hidden animate-nav-in !px-5 !py-2.5 text-[13.5px] ring-4 ring-brand-600/15 sm:inline-flex"
                       style="animation-delay: 560ms">
                        Collaborate
                        <x-ui-icon name="arrow-up-right" class="h-4 w-4 transition-transform duration-300 group-hover:rotate-45" />
                    </a>

                    <button type="button" @click="open = true"
                            class="menu-button flex h-10 items-center gap-2 rounded-full border border-ink-200 bg-white pl-4 pr-3
                                   text-[13.5px] font-semibold text-ink-800 transition duration-300 hover:border-brand-300 hover:text-brand-700 xl:hidden"
                            aria-label="Open menu">
                        Menu
                        <x-ui-icon name="menu" class="h-4.5 w-4.5" />
                    </button>
                </div>
            </div>

            {{-- Scroll progress --}}
            <div class="relative h-0.5 w-full" aria-hidden="true">
                <div class="h-0.5 origin-left bg-brand-600 transition-transform duration-150 ease-out"
                     :style="`transform: scaleX(${progress})`"></div>
            </div>
        </div>

        {{-- One full-width panel; its content swaps with the hovered section --}}
        <div x-show="panel" x-cloak
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-150 ease-in"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @mouseenter="keep()"
             class="absolute inset-x-0 top-full hidden border-b border-ink-100 bg-white shadow-[0_40px_80px_-50px_rgba(11,15,24,0.45)] xl:block">
            @foreach ($links as $link)
                @isset($link['panel'])
                    @php $many = count($link['panel']['children']) > 3; @endphp
                    <div x-show="panel === '{{ $link['key'] }}'"
                         x-transition:enter="transition duration-300 ease-out"
                         x-transition:enter-start="opacity-0 translate-x-3"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="container-rich grid grid-cols-[280px_1fr] gap-12 py-9">

                        <div class="border-r border-ink-100 pr-10">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-600">{{ $link['label'] }}</p>
                            <h3 class="mt-3 font-display text-[24px] font-bold leading-tight tracking-tight text-ink-950">{{ $link['panel']['title'] }}</h3>
                            <p class="mt-3 text-[14px] leading-relaxed muted">{{ $link['panel']['text'] }}</p>
                            <a href="{{ route($link['route']) }}"
                               class="group mt-6 inline-flex items-center gap-2 text-[14px] font-semibold text-brand-700">
                                View all
                                <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                            </a>
                        </div>

                        <div @class(['grid content-start gap-2', 'grid-cols-3' => $many, 'grid-cols-2 max-w-3xl' => ! $many])>
                            @foreach ($link['panel']['children'] as $j => [$childLabel, $childUrl, $childIcon, $childText])
                                <a href="{{ $childUrl }}"
                                   class="group flex items-start gap-3.5 rounded-2xl border border-transparent p-4 transition duration-300 hover:border-ink-100 hover:bg-ink-50">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition duration-300 group-hover:bg-brand-600 group-hover:text-white">
                                        <x-ui-icon :name="$childIcon" class="h-4.5 w-4.5" />
                                    </span>
                                    <span class="min-w-0">
                                        <span class="flex items-center gap-1 text-[14.5px] font-semibold leading-snug text-ink-950">
                                            {{ $childLabel }}
                                            <x-ui-icon name="arrow-up-right" class="h-3.5 w-3.5 -translate-x-1 opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100" />
                                        </span>
                                        @if ($childText)
                                            <span class="mt-1 block text-[13px] leading-snug muted">{{ $childText }}</span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endisset
            @endforeach
        </div>
    </div>

    {{-- Mobile / tablet: full-screen menu --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition duration-400 ease-out"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-200 ease-in"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex flex-col overflow-y-auto bg-ink-950 text-white xl:hidden"
         role="dialog" aria-modal="true" aria-label="Site menu">

        <div class="container-rich flex h-[100px] shrink-0 items-center justify-between">
            <x-brand-mark invert class="h-[72px]" />
            <button type="button" @click="open = false"
                    class="flex h-10 items-center gap-2 rounded-full border border-white/20 pl-4 pr-3 text-[13.5px] font-semibold transition hover:bg-white/10"
                    aria-label="Close menu">
                Close
                <x-ui-icon name="x" class="h-4.5 w-4.5" />
            </button>
        </div>

        <nav class="container-rich mt-4 flex flex-col" aria-label="Mobile">
            @foreach ($links as $i => $link)
                <div x-show="open"
                     x-transition:enter="transition duration-500 ease-out"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="transition-delay: {{ 80 + $i * 50 }}ms"
                     class="border-b border-white/10"
                     @isset($link['panel']) x-data="{ sub: false }" @endisset>
                    @isset($link['panel'])
                        <button type="button" @click="sub = ! sub" :aria-expanded="sub"
                                class="flex w-full items-center gap-4 py-4 text-left">
                            <span class="w-6 font-mono text-[12px] text-white/40">{{ sprintf('%02d', $i + 1) }}</span>
                            <span class="flex-1 font-display text-[26px] font-semibold tracking-tight">{{ $link['label'] }}</span>
                            <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/20">
                                <x-ui-icon name="plus" class="h-4 w-4 transition-transform duration-300" ::class="sub && 'rotate-45'" />
                            </span>
                        </button>
                        <div x-show="sub" x-collapse>
                            <div class="grid gap-1 pb-5 pl-10 sm:grid-cols-2">
                                @foreach ($link['panel']['children'] as [$childLabel, $childUrl, $childIcon])
                                    <a href="{{ $childUrl }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[15px] text-white/75 transition hover:bg-white/5 hover:text-white">
                                        <x-ui-icon :name="$childIcon" class="h-4 w-4 shrink-0 text-brand-300" /> {{ $childLabel }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route($link['route']) }}" class="flex items-center gap-4 py-4">
                            <span class="w-6 font-mono text-[12px] text-white/40">{{ sprintf('%02d', $i + 1) }}</span>
                            <span class="flex-1 font-display text-[26px] font-semibold tracking-tight">{{ $link['label'] }}</span>
                            <x-ui-icon name="arrow-up-right" class="h-5 w-5 text-white/40" />
                        </a>
                    @endisset
                </div>
            @endforeach
        </nav>

        <div class="container-rich mt-auto grid gap-6 pb-10 pt-12 sm:grid-cols-2 sm:items-end">
            <div class="text-sm text-white/60">
                @if ($email)
                    <a href="mailto:{{ $email }}" class="block transition hover:text-white">{{ $email }}</a>
                @endif
                @if ($phone)
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="mt-1 block transition hover:text-white">{{ $phone }}</a>
                @endif
            </div>
            <a href="{{ route('contact') }}" class="btn-primary w-full sm:w-auto sm:justify-self-end">
                Collaborate with us
                <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</header>
