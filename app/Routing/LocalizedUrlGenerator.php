<?php

namespace App\Routing;

use Illuminate\Routing\UrlGenerator;

/**
 * The public site is registered twice: at the root for Bangla and under /en
 * for English, where every route name carries an `en.` prefix. Views should
 * not have to know that, so route('about') resolves to whichever copy belongs
 * to the language currently being read.
 */
class LocalizedUrlGenerator extends UrlGenerator
{
    public function route($name, $parameters = [], $absolute = true)
    {
        if (is_string($name)
            && app()->getLocale() !== 'bn'
            && ! str_starts_with($name, 'en.')
            && $this->routes->hasNamedRoute('en.'.$name)) {
            $name = 'en.'.$name;
        }

        return parent::route($name, $parameters, $absolute);
    }

    /** The same route in the other language, for the header's switcher. */
    public function inLocale(string $locale, string $name, array $parameters = []): string
    {
        $name = ltrim(str_replace('en.', '', $name), '.');

        if ($locale !== 'bn') {
            $name = 'en.'.$name;
        }

        return $this->routes->hasNamedRoute($name)
            ? parent::route($name, $parameters)
            : $this->to($locale === 'bn' ? '/' : '/en');
    }
}
