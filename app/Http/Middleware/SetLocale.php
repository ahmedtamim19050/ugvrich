<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bangla is the site's own language and lives at the root; English is the
 * same site one segment deeper, under /en. The first path segment decides
 * which, and LocalizedUrlGenerator keeps every route() call in that language.
 */
class SetLocale
{
    public const LOCALES = ['bn' => 'বাংলা', 'en' => 'English'];

    public const DEFAULT = 'bn';

    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($request->segment(1) === 'en' ? 'en' : self::DEFAULT);

        return $next($request);
    }
}
