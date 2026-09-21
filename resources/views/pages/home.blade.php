<x-layouts.app :description="$site->get('hero_subheading')">
    @include('partials.home.hero')
    @include('partials.home.dashboard')
    @include('partials.home.about')
</x-layouts.app>
