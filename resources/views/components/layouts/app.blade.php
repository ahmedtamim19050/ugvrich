@props([
    'title' => null,
    'description' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-pt-28">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="64x64">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <title>{{ $title ? $title.' — '.$site->name() : $site->name().' · '.$site->tagline() }}</title>
    <meta name="description" content="{{ $description ?? $site->get('site_motto') }}">

    <meta property="og:title" content="{{ $title ?? $site->name() }}">
    <meta property="og:description" content="{{ $description ?? $site->get('site_motto') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">
    <a href="#main"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full
              focus:bg-brand-600 focus:px-5 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white">
        {{ __('site.cards.skip_to_content') }}
    </a>

    <x-site-header />

    <main id="main">
        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
