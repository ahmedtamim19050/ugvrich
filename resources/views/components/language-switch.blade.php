@props(['invert' => false])

@php
    use App\Http\Middleware\SetLocale;

    $current = app()->getLocale();
    $other = $current === 'bn' ? 'en' : 'bn';

    // The same page in the other language: same route, same parameters.
    $name = \Illuminate\Support\Str::of((string) request()->route()?->getName())->after('en.')->toString();
    $parameters = collect(request()->route()?->parameters() ?? [])->all();

    $target = $name
        ? app('url')->inLocale($other, $name, $parameters)
        : url($other === 'bn' ? '/' : '/en');
@endphp

<a href="{{ $target }}"
   hreflang="{{ $other }}"
   title="{{ __('site.language.switch_to', ['language' => __('site.language.'.$other)]) }}"
   {{ $attributes->class([
       'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[12.5px] font-semibold transition duration-300',
       'border-white/25 text-white/80 hover:border-white hover:text-white' => $invert,
       'border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-700' => ! $invert,
   ]) }}>
    <x-ui-icon name="globe" class="h-3.5 w-3.5" />
    {{ __('site.language.'.$other) }}
</a>
